<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_admin_requires_credentials(): void
    {
        $this->get('/admin')->assertUnauthorized();
        $this->withServerVariables(['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'wrong'])->get('/admin')->assertUnauthorized();
        $this->get('/admin/export/orders')->assertUnauthorized();
        $this->withServerVariables($this->admin())->get('/admin')->assertOk();
    }

    public function test_admin_is_closed_when_no_password_is_configured(): void
    {
        config(['app.admin.password' => '']);
        $this->withServerVariables($this->admin())->get('/admin')->assertStatus(503);
    }

    public function test_public_pages_do_not_link_to_admin(): void
    {
        $this->get('/')->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_add_unit_builds_a_consistent_breakdown(): void
    {
        $this->withServerVariables($this->admin())->post('/admin/units', [
            'project_slug' => 'kedia-aravali',
            'unit_code' => 'ZZ-T1',
            'unit_type' => 'Plot',
            'configuration' => '150 Sq Yd Plot',
            'status' => 'available',
            'area_sqft' => 1350,
            'total_price' => 3000000,
        ])->assertSessionHasNoErrors();

        $unit = Unit::where('unit_code', 'ZZ-T1')->sole();
        $pricing = $unit->computePricing();
        $this->assertSame(3000000, $pricing['grand_total']);
        $this->assertSame(0, $pricing['gst']);
        $this->assertSame(0, $pricing['other_charges']);
        $this->assertSame('ZZ-T1', $unit->unit_number);
    }

    public function test_duplicate_unit_code_in_same_project_is_rejected(): void
    {
        $existing = Unit::first();
        $this->withServerVariables($this->admin())->post('/admin/units', [
            'project_slug' => $existing->project_slug,
            'unit_code' => $existing->unit_code,
            'unit_type' => 'Villa',
            'status' => 'available',
            'area_sqft' => 1600,
            'total_price' => 4500000,
        ])->assertSessionHasErrors('unit_code');
    }

    public function test_price_edit_rescales_the_breakdown(): void
    {
        $villa = Unit::where('unit_type', 'Villa')->first();
        $this->withServerVariables($this->admin())->postJson("/admin/units/{$villa->id}/price", ['price_num' => 12000000])->assertOk();

        $pricing = $villa->fresh()->computePricing();
        $this->assertSame(12000000, $pricing['grand_total']);
        $this->assertSame(0, $pricing['other_charges']);
        $this->assertGreaterThan(0, $pricing['gst']);
    }

    public function test_verifying_and_cancelling_a_booking(): void
    {
        $unit = Unit::where('status', 'available')->first();
        $this->postJson('/booking/store', [
            'unit_id' => $unit->id, 'customer_name' => 'T', 'customer_email' => 't@example.com',
            'customer_phone' => '9876543210', 'payment_method' => 'upi',
        ])->assertOk();
        $booking = Booking::latest('id')->first();

        $revenue = fn () => Booking::whereIn('status', Booking::RECEIVED_STATUSES)->sum('token_amount_paid');
        $before = $revenue();

        $this->withServerVariables($this->admin())->post("/admin/bookings/{$booking->id}/status", ['booking_status' => 'confirmed']);
        $booking->refresh();
        $this->assertSame('Paid', $booking->payment_schedule[0]['status']);
        $this->assertEquals($before + $booking->token_amount_paid, $revenue());

        $this->withServerVariables($this->admin())->post("/admin/bookings/{$booking->id}/status", ['booking_status' => 'cancelled']);
        $this->assertSame('available', $unit->fresh()->status);
    }
}
