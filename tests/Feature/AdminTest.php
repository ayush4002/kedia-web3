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
            'customer_phone' => '9876543210', 'payment_method' => 'upi', 'accept_terms' => '1',
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

    public function test_admin_sections_render(): void
    {
        $unit = Unit::first();
        $project = \App\Models\Project::first();
        foreach (['/admin', '/admin/inventory', '/admin/projects', '/admin/projects/create', "/admin/projects/{$project->id}/edit",
                  "/admin/units/{$unit->id}/edit", '/admin/bookings', '/admin/leads', '/admin/leads?due=1'] as $url) {
            $this->withServerVariables($this->admin())->get($url)->assertOk();
        }
    }

    public function test_project_can_be_created_and_edited(): void
    {
        $this->withServerVariables($this->admin())->post('/admin/projects', [
            'name' => 'Kedia Test Enclave', 'type' => 'Villas', 'classification' => 'Residential', 'status' => 'ongoing',
            'location' => 'Ajmer Road', 'rera_number' => 'RAJ/P/2026/0001', 'amenities_text' => "Clubhouse\nPark",
        ])->assertRedirect(route('admin.projects'));

        $project = \App\Models\Project::where('slug', 'kedia-test-enclave')->sole();
        $this->assertSame(['Clubhouse', 'Park'], $project->amenities);
        $this->get('/projects/kedia-test-enclave')->assertOk()->assertSee('RAJ/P/2026/0001');

        $this->withServerVariables($this->admin())->put("/admin/projects/{$project->id}", [
            'name' => 'Kedia Test Enclave II', 'type' => 'Villas', 'classification' => 'Residential', 'status' => 'completed', 'location' => 'Ajmer Road',
        ])->assertRedirect(route('admin.projects'));
        $this->assertSame('completed', $project->fresh()->status);
    }

    public function test_unit_edit_keeps_the_breakdown_consistent(): void
    {
        $unit = Unit::where('unit_type', 'Villa')->first();
        $this->withServerVariables($this->admin())->put("/admin/units/{$unit->id}", [
            'unit_number' => 'Villa 1A', 'unit_type' => 'Villa', 'configuration' => '4 BHK Villa', 'area_sqft' => 2400,
            'status' => 'available', 'total_price' => 15000000,
        ])->assertRedirect();

        $unit->refresh();
        $this->assertSame('Villa 1A', $unit->unit_number);
        $this->assertSame(15000000, $unit->computePricing()['grand_total']);
        $this->assertSame(0, $unit->computePricing()['other_charges']);
    }

    public function test_lead_can_be_assigned_and_scheduled(): void
    {
        $lead = \App\Models\SiteVisitLead::create(['lead_code' => 'LD-TEST-1', 'name' => 'Lead', 'phone' => '9999999999', 'project' => 'General Inquiry', 'status' => 'new']);

        $this->withServerVariables($this->admin())->put("/admin/leads/{$lead->id}", [
            'status' => 'contacted', 'assigned_advisor' => 'NRI Desk', 'follow_up_at' => now()->subDay()->toDateString(), 'notes' => 'Call back',
        ])->assertRedirect();

        $lead->refresh();
        $this->assertSame('contacted', $lead->status);
        $this->assertSame('NRI Desk', $lead->assigned_advisor);
        $this->assertTrue($lead->is_overdue);

        // Advisors outside the configured list are rejected.
        $this->withServerVariables($this->admin())->put("/admin/leads/{$lead->id}", ['status' => 'new', 'assigned_advisor' => 'Someone Else'])
            ->assertSessionHasErrors('assigned_advisor');
    }

    public function test_csv_export_neutralises_formulas(): void
    {
        Booking::query()->update(['customer_name' => '=HYPERLINK("http://evil")']);
        $csv = $this->withServerVariables($this->admin())->get('/admin/export/orders')->streamedContent();
        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }

    public function test_unit_with_active_booking_cannot_be_deleted(): void
    {
        $booking = Booking::whereNotNull('unit_id')->first();
        $this->withServerVariables($this->admin())->delete("/admin/units/{$booking->unit_id}")->assertSessionHas('error');
        $this->assertNotNull(Unit::find($booking->unit_id));
    }
}
