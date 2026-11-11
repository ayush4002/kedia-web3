<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Project;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    /**
     * Customer Portal & Holding Dashboard.
     */
    public function index(Request $request)
    {
        // A booking is only shown when the visitor supplies BOTH the exact booking ID
        // and the phone number it was booked with. Nothing is listed by default.
        $primaryBooking = null;
        $lookupFailed = false;

        $bookingId = strtoupper(trim((string) $request->query('booking_id', '')));
        $phone = $this->lastTenDigits((string) $request->query('phone', ''));

        if ($bookingId !== '' || $phone !== '') {
            $booking = $bookingId !== '' && strlen($phone) === 10
                ? Booking::with(['unit', 'project'])->where('booking_id', $bookingId)->first()
                : null;

            if ($booking && hash_equals($this->lastTenDigits($booking->customer_phone), $phone)) {
                $primaryBooking = $booking;
            } else {
                $lookupFailed = true;
            }
        }

        $bookings = collect($primaryBooking ? [$primaryBooking] : []);

        // Sample construction milestone track
        $constructionTimeline = [
            ['phase' => 'JDA Sanction & Land Clearance', 'progress' => 100, 'date' => 'Completed - Jan 2025', 'status' => 'done'],
            ['phase' => 'Sub-structure & Raft Foundation', 'progress' => 100, 'date' => 'Completed - Apr 2025', 'status' => 'done'],
            ['phase' => 'Superstructure RCC Framing', 'progress' => 85, 'date' => 'In Progress - Oct 2026', 'status' => 'current'],
            ['phase' => 'Internal Finishing & Jaali Facade', 'progress' => 20, 'date' => 'Upcoming - Mar 2027', 'status' => 'pending'],
            ['phase' => 'Possession & RERA Completion Handover', 'progress' => 0, 'date' => 'Target - Dec 2027', 'status' => 'pending'],
        ];

        return view('pages.customer.dashboard', compact('bookings', 'primaryBooking', 'constructionTimeline', 'lookupFailed'));
    }

    private function lastTenDigits(string $phone): string
    {
        return substr(preg_replace('/\D+/', '', $phone), -10);
    }
}
