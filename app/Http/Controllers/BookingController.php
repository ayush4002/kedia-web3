<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Step 1 - 4: E-Commerce Style Booking Checkout View.
     */
    public function checkout(int $unit)
    {
        $unit = Unit::with('project')->findOrFail($unit);

        if ($unit->status !== 'available') {
            return redirect()->route('units.show', [$unit->project_slug, $unit->unit_code])
                ->with('error', 'This unit has already been reserved or sold.');
        }

        $pricing = $unit->computePricing();

        return view('pages.booking.checkout', compact('unit', 'pricing'));
    }

    /**
     * Process Token Booking and Simulate Payment Gateway (Test Mode).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_name' => 'required|string|max:120',
            'customer_email' => 'required|email|max:120',
            'customer_phone' => 'required|string|max:20',
            'customer_pan' => 'nullable|string|max:20',
            'customer_aadhaar' => 'nullable|string|max:20',
            'customer_city' => 'nullable|string|max:80',
            'payment_method' => 'required|string|in:upi,card,netbanking,neft',
            'notes' => 'nullable|string|max:500',
        ]);

        // Lock the unit row so two buyers cannot reserve the same unit at the same time.
        $booking = DB::transaction(function () use ($validated) {
            $unit = Unit::lockForUpdate()->find($validated['unit_id']);

            if (!$unit || $unit->status !== 'available') {
                return null;
            }

            $booking = $this->createBooking($unit, $validated);

            $unit->status = 'on_hold';
            $unit->save();

            return $booking;
        });

        if (!$booking) {
            $message = 'Sorry, this unit was just reserved or sold by another buyer.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->withInput()->with('error', $message);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $booking->voucherUrl(),
                'booking' => $booking,
            ]);
        }

        return redirect()->to($booking->voucherUrl());
    }

    private function createBooking(Unit $unit, array $validated): Booking
    {
        $pricing = $unit->computePricing();

        do {
            $bookingId = 'KB-' . strtoupper(Str::random(7));
        } while (Booking::where('booking_id', $bookingId)->exists());

        return Booking::create([
            'booking_id' => $bookingId,
            'unit_id' => $unit->id,
            'unit_code' => $unit->unit_code,
            'unit_number' => $unit->unit_number,
            'project_slug' => $unit->project_slug,
            'project_name' => $unit->project_name,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_pan' => $validated['customer_pan'] ?? null,
            'customer_aadhar' => $validated['customer_aadhaar'] ?? null,
            'address' => $validated['customer_city'] ?? null,
            'booking_date' => now(),
            'token_amount_paid' => $pricing['token_amount'],
            'total_unit_amount' => $pricing['grand_total'],
            'payment_method' => $validated['payment_method'],
            // No real payment gateway is connected yet: the token is a simulated (test)
            // payment, so the booking waits for the sales team to verify it in admin.
            'payment_reference' => 'TEST-' . strtoupper(Str::random(10)),
            'status' => Booking::STATUS_PENDING_VERIFICATION,
            'cost_breakdown' => $pricing,
            'payment_schedule' => $this->paymentSchedule($pricing['grand_total'], $pricing['token_amount']),
        ]);
    }

    /**
     * Milestone schedule whose amounts always add up exactly to the grand total.
     */
    private function paymentSchedule(int $total, int $token): array
    {
        // [label, weight of the post-token balance, due in days]
        $stages = [
            ['On Execution of Agreement to Sale (30 Days)', 20, 30],
            ['On Foundation & Plinth Completion', 35, 90],
            ['On Superstructure & Internal Plaster', 30, 180],
            ['On Registry & Possession Handover', 10, 270],
        ];
        $weightSum = array_sum(array_column($stages, 1));

        $schedule = [[
            'milestone' => 'Token Advance Booking',
            'percentage' => round($token / max($total, 1) * 100, 2),
            'amount' => $token,
            'status' => 'Pending Verification',
            'due_date' => now()->format('Y-m-d'),
        ]];

        $balance = $total - $token;
        $allocated = 0;
        foreach ($stages as $i => [$label, $weight, $days]) {
            $amount = $i === array_key_last($stages)
                ? $balance - $allocated
                : (int) round($balance * $weight / $weightSum);
            $allocated += $amount;
            $schedule[] = [
                'milestone' => $label,
                'percentage' => round($amount / max($total, 1) * 100, 2),
                'amount' => $amount,
                'status' => 'Upcoming',
                'due_date' => now()->addDays($days)->format('Y-m-d'),
            ];
        }

        return $schedule;
    }

    /**
     * Display Booking Confirmation and Downloadable Receipt.
     */
    public function confirmation($bookingId)
    {
        $booking = Booking::with(['unit', 'project'])
            ->where('booking_id', $bookingId)
            ->firstOrFail();

        return view('pages.booking.confirmation', compact('booking'));
    }
}
