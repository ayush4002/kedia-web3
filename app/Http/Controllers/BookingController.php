<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Cart & checkout:
 *   1. checkout()        cost sheet + buyer details + payment method
 *   2. store()           creates the booking (awaiting payment) and puts the unit on hold
 *   3. payment()         TEST-MODE payment gateway screen (no real gateway is connected)
 *   4. processPayment()  simulated success / failure
 *   5. confirmation()    order confirmation, receipt and hold timer (signed link)
 */
class BookingController extends Controller
{
    public function checkout(int $unit)
    {
        $unit = Unit::with('project')->findOrFail($unit);

        if ($unit->status !== 'available') {
            return redirect()->route('units.show', [$unit->project_slug, $unit->unit_code])
                ->with('error', __('This unit has already been reserved or sold.'));
        }

        $pricing = $unit->computePricing();

        return view('pages.booking.checkout', compact('unit', 'pricing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_name' => 'required|string|max:120',
            'customer_email' => 'required|email|max:120',
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,20}$/'],
            'customer_pan' => ['nullable', 'string', 'regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/'],
            'customer_aadhaar' => ['nullable', 'digits:4'],
            'customer_city' => 'nullable|string|max:80',
            'payment_method' => 'required|string|in:upi,card,netbanking',
            'accept_terms' => 'accepted',
        ], [
            'customer_pan.regex' => __('Enter a valid PAN (for example ABCDE1234F).'),
            'customer_phone.regex' => __('Enter a valid mobile number.'),
            'accept_terms.accepted' => __('Please accept the booking terms to continue.'),
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
            $message = __('Sorry, this unit was just reserved or sold by another buyer.');
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->withInput()->with('error', $message);
        }

        // Pre-fill the account sign-in with this number. (Never sign in without an OTP:
        // the number typed at checkout is not verified.)
        $request->session()->put('account_phone_hint', Booking::normalisePhone($booking->customer_phone));

        $payUrl = URL::signedRoute('booking.pay', ['bookingId' => $booking->booking_id]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect_url' => $payUrl, 'booking_id' => $booking->booking_id]);
        }

        return redirect()->to($payUrl);
    }

    /**
     * Test-mode payment gateway screen.
     */
    public function payment(string $bookingId)
    {
        $booking = Booking::with(['unit', 'project'])->where('booking_id', $bookingId)->firstOrFail();

        if ($booking->status !== Booking::STATUS_AWAITING_PAYMENT) {
            return redirect()->to($booking->voucherUrl());
        }

        $processUrl = URL::signedRoute('booking.pay.process', ['bookingId' => $booking->booking_id]);

        return view('pages.booking.payment', compact('booking', 'processUrl'));
    }

    /**
     * Simulated gateway callback. No money moves: "success" marks the token as a
     * test payment awaiting verification, "failure" cancels and releases the unit.
     */
    public function processPayment(Request $request, string $bookingId)
    {
        $request->validate(['outcome' => 'required|in:success,failure']);

        $booking = Booking::with('unit')->where('booking_id', $bookingId)->firstOrFail();

        if ($booking->status !== Booking::STATUS_AWAITING_PAYMENT) {
            return redirect()->to($booking->voucherUrl());
        }

        if ($request->input('outcome') === 'failure') {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'cancelled']);
                if ($booking->unit && $booking->unit->status === 'on_hold') {
                    $booking->unit->update(['status' => 'available']);
                }
            });

            return redirect()->route('booking.checkout', $booking->unit_id)
                ->with('error', __('The test payment was declined, so nothing was charged and the unit is available again. You can try once more.'));
        }

        $booking->update([
            'status' => Booking::STATUS_PENDING_VERIFICATION,
            'payment_reference' => 'TEST-' . strtoupper(Str::random(10)),
            'booking_date' => now(),
        ]);

        return redirect()->to($booking->voucherUrl())->with('success', __('Test payment recorded. Your unit is on hold.'));
    }

    public function confirmation(string $bookingId)
    {
        $booking = Booking::with(['unit', 'project'])
            ->where('booking_id', $bookingId)
            ->firstOrFail();

        if ($booking->status === Booking::STATUS_AWAITING_PAYMENT) {
            return redirect()->to(URL::signedRoute('booking.pay', ['bookingId' => $booking->booking_id]));
        }

        return view('pages.booking.confirmation', compact('booking'));
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
            'customer_pan' => isset($validated['customer_pan']) ? strtoupper($validated['customer_pan']) : null,
            'customer_aadhar' => $validated['customer_aadhaar'] ?? null,
            'address' => $validated['customer_city'] ?? null,
            'booking_date' => now(),
            'token_amount_paid' => $pricing['token_amount'],
            'total_unit_amount' => $pricing['grand_total'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => null,
            'status' => Booking::STATUS_AWAITING_PAYMENT,
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
}
