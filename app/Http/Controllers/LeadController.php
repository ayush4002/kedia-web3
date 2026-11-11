<?php

namespace App\Http\Controllers;

use App\Models\SiteVisitLead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    /**
     * Store an inbound site visit request or callback inquiry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:120',
            'project_name' => 'nullable|string|max:120',
            'preferred_date' => 'nullable|date',
            'time_slot' => 'nullable|string|max:50',
            'pickup_requested' => 'nullable|boolean',
            'source' => 'nullable|string|max:60',
            'message' => 'nullable|string|max:500',
        ]);

        // site_visit_leads has no pickup column, so the chauffeur request is kept in the message.
        $message = $validated['message'] ?? null;
        if ($request->boolean('pickup_requested')) {
            $message = trim(($message ? $message . "\n" : '') . 'Chauffeur pickup requested.');
        }

        $lead = SiteVisitLead::create([
            'lead_code' => $this->generateLeadCode(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'project' => $validated['project_name'] ?? 'General Inquiry',
            'preferred_date' => $validated['preferred_date'] ?? now()->addDay()->toDateString(),
            'preferred_time' => $validated['time_slot'] ?? '11:00 AM - 01:00 PM',
            'source' => $validated['source'] ?? 'website_modal',
            'message' => $message,
            'status' => 'new',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Our relationship manager will confirm your private site tour shortly.',
                'lead' => $lead,
            ]);
        }

        return back()->with('success', 'Thank you! Our relationship manager will contact you at ' . $validated['phone'] . ' shortly to confirm your visit.');
    }

    private function generateLeadCode(): string
    {
        do {
            $code = 'LD-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (SiteVisitLead::where('lead_code', $code)->exists());

        return $code;
    }
}
