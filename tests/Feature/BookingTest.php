<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function buyer(Unit $unit, array $overrides = []): array
    {
        return array_merge([
            'unit_id' => $unit->id,
            'customer_name' => 'Test Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '+91 98765 43210',
            'payment_method' => 'upi',
            'accept_terms' => '1',
        ], $overrides);
    }

    /** Checkout -> gateway URL. Returns [booking, payUrl]. */
    private function startBooking(Unit $unit): array
    {
        $response = $this->postJson('/booking/store', $this->buyer($unit))->assertOk()->assertJson(['success' => true]);
        $booking = Booking::where('booking_id', $response->json('booking_id'))->sole();

        return [$booking, $response->json('redirect_url')];
    }

    private function processUrl(Booking $booking): string
    {
        return \Illuminate\Support\Facades\URL::signedRoute('booking.pay.process', ['bookingId' => $booking->booking_id]);
    }

    public function test_every_unit_cost_sheet_matches_its_listed_price(): void
    {
        foreach (Unit::all() as $unit) {
            $pricing = $unit->computePricing();
            $this->assertSame($unit->total_price, $pricing['grand_total'], "unit {$unit->id}");
            $this->assertSame((int) round($unit->total_price * 0.05), $pricing['token_amount']);
        }

        $plot = Unit::where('unit_type', 'Plot')->first();
        $this->assertSame(0, $plot->computePricing()['gst']);
    }

    public function test_unit_page_checkout_and_cost_sheet_show_the_same_price(): void
    {
        $unit = Unit::where('status', 'available')->where('unit_type', 'Plot')->first();
        $price = \App\Support\Format::inr($unit->total_price);

        $this->get(route('units.show', [$unit->project_slug, $unit->unit_code]))
            ->assertOk()->assertSee($price)->assertSee('No GST applies to this unit.')->assertSee('"@type":"Product"', false);
        $this->get(route('booking.checkout', $unit->id))->assertOk()->assertSee($price);
        $this->get(route('units.cost-sheet', $unit->id))->assertOk()->assertSee($price);
    }

    public function test_checkout_holds_the_unit_and_sends_the_buyer_to_the_test_gateway(): void
    {
        $unit = Unit::where('status', 'available')->first();
        [$booking, $payUrl] = $this->startBooking($unit);

        $this->assertSame(Booking::STATUS_AWAITING_PAYMENT, $booking->status);
        $this->assertSame('on_hold', $unit->fresh()->status);
        $this->assertSame($booking->total_unit_amount, array_sum(array_column($booking->payment_schedule, 'amount')));
        $this->assertStringContainsString('signature=', $payUrl);

        $this->get($payUrl)->assertOk()->assertSee('Demo gateway');
        $this->get('/booking/pay/' . $booking->booking_id)->assertForbidden(); // unsigned
    }

    public function test_successful_test_payment_leads_to_signed_voucher(): void
    {
        $unit = Unit::where('status', 'available')->first();
        [$booking] = $this->startBooking($unit);

        $response = $this->post($this->processUrl($booking), ['outcome' => 'success']);
        $booking->refresh();

        $response->assertRedirect($booking->voucherUrl());
        $this->assertSame(Booking::STATUS_PENDING_VERIFICATION, $booking->status);
        $this->assertStringStartsWith('TEST-', $booking->payment_reference);
        $this->assertSame('pending', $booking->payment_status);

        $this->get($booking->voucherUrl())->assertOk()->assertSee('Payment Verification Pending')->assertSee('Unit held for you until')->assertDontSee('has been dispatched');
        $this->get('/booking/confirmation/' . $booking->booking_id)->assertForbidden();
    }

    public function test_declined_test_payment_releases_the_unit(): void
    {
        $unit = Unit::where('status', 'available')->first();
        [$booking] = $this->startBooking($unit);

        $this->post($this->processUrl($booking), ['outcome' => 'failure'])->assertRedirect(route('booking.checkout', $unit->id));

        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertSame('available', $unit->fresh()->status);
    }

    public function test_a_unit_cannot_be_booked_twice(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->startBooking($unit);
        $this->postJson('/booking/store', $this->buyer($unit))->assertStatus(422);
        $this->assertSame(1, Booking::where('unit_id', $unit->id)->count());

        $this->get(route('booking.checkout', $unit->id))->assertRedirect();
    }

    public function test_checkout_validates_terms_and_pan(): void
    {
        $unit = Unit::where('status', 'available')->first();

        $this->postJson('/booking/store', $this->buyer($unit, ['accept_terms' => null]))->assertStatus(422)->assertJsonValidationErrors('accept_terms');
        $this->postJson('/booking/store', $this->buyer($unit, ['customer_pan' => 'NOTAPAN']))->assertStatus(422)->assertJsonValidationErrors('customer_pan');
        $this->assertSame('available', $unit->fresh()->status);
    }

    public function test_checkout_does_not_sign_the_buyer_in(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->startBooking($unit);

        // The phone typed at checkout is unverified, so the account still asks for an OTP.
        $this->get('/account')->assertOk()->assertSee('Sign in with your mobile')->assertSee('value="9876543210"', false);
    }

    public function test_checkout_has_no_prefilled_personal_data(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->get(route('booking.checkout', $unit->id))
            ->assertDontSee('Ayush')
            ->assertDontSee('value="ABCDE1234F"', false);
    }
}
