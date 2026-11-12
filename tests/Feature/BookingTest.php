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

    private function buyer(Unit $unit): array
    {
        return [
            'unit_id' => $unit->id,
            'customer_name' => 'Test Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '+91 98765 43210',
            'payment_method' => 'upi',
        ];
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

    public function test_unit_page_and_checkout_show_the_same_price(): void
    {
        $unit = Unit::where('status', 'available')->where('unit_type', 'Plot')->first();
        $price = \App\Support\Format::inr($unit->total_price);

        $this->get(route('units.show', [$unit->project_slug, $unit->unit_code]))
            ->assertOk()->assertSee($price)->assertSee('No GST applies to this unit.');
        $this->get(route('booking.checkout', $unit->id))->assertOk()->assertSee($price);
    }

    public function test_booking_is_held_pending_verification(): void
    {
        $unit = Unit::where('status', 'available')->first();

        $response = $this->postJson('/booking/store', $this->buyer($unit))->assertOk()->assertJson(['success' => true]);

        $booking = Booking::latest('id')->first();
        $this->assertSame(Booking::STATUS_PENDING_VERIFICATION, $booking->status);
        $this->assertStringStartsWith('TEST-', $booking->payment_reference);
        $this->assertSame($unit->id, $booking->unit_id);
        $this->assertSame('on_hold', $unit->fresh()->status);
        $this->assertSame($booking->total_unit_amount, array_sum(array_column($booking->payment_schedule, 'amount')));
        $this->assertSame('pending', $booking->payment_status);

        // The voucher is only reachable through the signed link.
        $voucher = $response->json('redirect_url');
        $this->get($voucher)->assertOk()->assertSee('Payment Verification Pending')->assertDontSee('has been dispatched');
        $this->get('/booking/confirmation/' . $booking->booking_id)->assertForbidden();
    }

    public function test_a_unit_cannot_be_booked_twice(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->postJson('/booking/store', $this->buyer($unit))->assertOk();
        $this->postJson('/booking/store', $this->buyer($unit))->assertStatus(422);
        $this->assertSame(1, Booking::where('unit_id', $unit->id)->count());

        $this->get(route('booking.checkout', $unit->id))->assertRedirect();
    }

    public function test_checkout_has_no_prefilled_personal_data(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->get(route('booking.checkout', $unit->id))
            ->assertDontSee('Ayush')
            ->assertDontSee('value="ABCDE1234F"', false);
    }
}
