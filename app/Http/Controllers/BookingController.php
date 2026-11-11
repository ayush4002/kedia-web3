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
    public function checkout($unitCode)
    {
        $unit = Unit::with('project')
            ->where('unit_code', $unitCode)
            ->orWhere('id', $unitCode)
            ->firstOrFail();

        if ($unit->status !== 'available') {
            return redirect()->route('units.show', $unit->unit_code)
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
                'redirect_url' => route('booking.confirmation', ['bookingId' => $booking->booking_id]),
                'booking' => $booking,
            ]);
        }

        return redirect()->route('booking.confirmation', ['bookingId' => $booking->booking_id]);
    }

    private function createBooking(Unit $unit, array $validated): Booking
    {
        $pricing = $unit->computePricing();

        do {
            $bookingId = 'KB-' . strtoupper(Str::random(7));
        } while (Booking::where('booking_id', $bookingId)->exists());

        return Booking::create([
            'booking_id' => $bookingId,
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
            'payment_reference' => 'TXN-' . strtoupper(Str::random(10)),
            'status' => 'confirmed',
            'cost_breakdown' => $pricing,
            'payment_schedule' => [
                ['milestone' => 'Token Advance Booking', 'percentage' => 5, 'amount' => $pricing['token_amount'], 'status' => 'Paid', 'due_date' => now()->format('Y-m-d')],
                ['milestone' => 'On Execution of Agreement to Sale (30 Days)', 'percentage' => 20, 'amount' => round($pricing['grand_total'] * 0.20), 'status' => 'Upcoming', 'due_date' => now()->addDays(30)->format('Y-m-d')],
                ['milestone' => 'On Foundation & Plinth Completion', 'percentage' => 35, 'amount' => round($pricing['grand_total'] * 0.35), 'status' => 'Upcoming', 'due_date' => now()->addDays(90)->format('Y-m-d')],
                ['milestone' => 'On Superstructure & Internal Plaster', 'percentage' => 30, 'amount' => round($pricing['grand_total'] * 0.30), 'status' => 'Upcoming', 'due_date' => now()->addDays(180)->format('Y-m-d')],
                ['milestone' => 'On Registry & Possession Handover', 'percentage' => 10, 'amount' => round($pricing['grand_total'] * 0.10) - $pricing['token_amount'], 'status' => 'Upcoming', 'due_date' => now()->addDays(270)->format('Y-m-d')],
            ],
        ]);
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
