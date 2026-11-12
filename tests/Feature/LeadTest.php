<?php

namespace Tests\Feature;

use App\Models\SiteVisitLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_visit_lead_is_saved(): void
    {
        $this->from('/')->post('/lead/site-visit', [
            'name' => 'Test Buyer',
            'phone' => '9999999999',
            'project_name' => "Kedia's Landmark",
            'preferred_date' => '2026-12-01',
            'time_slot' => '10:00 AM - 12:00 PM',
            'pickup_requested' => '1',
        ])->assertRedirect('/')->assertSessionHas('success');

        $lead = SiteVisitLead::sole();
        $this->assertMatchesRegularExpression('/^LD-\d{6}-[A-Z0-9]{5}$/', $lead->lead_code);
        $this->assertSame("Kedia's Landmark", $lead->project);
        $this->assertSame('10:00 AM - 12:00 PM', $lead->preferred_time);
        $this->assertStringContainsString('Chauffeur pickup requested', $lead->message);
    }

    public function test_contact_form_lead_defaults(): void
    {
        $this->postJson('/lead/site-visit', ['name' => 'C', 'phone' => '9999999999', 'source' => 'contact_page', 'message' => 'Hi'])
            ->assertOk()->assertJson(['success' => true]);

        $lead = SiteVisitLead::sole();
        $this->assertSame('General Inquiry', $lead->project);
        $this->assertSame('contact_page', $lead->source);
    }

    public function test_invalid_lead_shows_errors(): void
    {
        $this->from('/contact')->post('/lead/site-visit', ['phone' => '1'])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors('name');

        $this->assertSame(0, SiteVisitLead::count());
    }
}
