<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = [
            [
                'booking_id' => 'KED-2026-7821',
                'unit_code' => 'NIK-Villa102',
                'unit_number' => 'Villa 102',
                'project_slug' => 'nikunj-villas',
                'project_name' => 'Nikunj Villas',
                'customer_name' => 'Dr. Vikramaditya Rathore',
                'customer_email' => 'vikram.rathore@example.com',
                'customer_phone' => '+91 98290 44123',
                'customer_pan' => 'ABCDE1234F',
                'customer_aadhar' => 'XXXX-XXXX-8912',
                'address' => 'C-42, Tilak Nagar, Jaipur',
                'booking_date' => '2026-09-15 10:30:00',
                'token_amount_paid' => 100000,
                'total_unit_amount' => 8850000,
                'payment_method' => 'UPI',
                'payment_reference' => 'UPI/20260915/789123891',
                'status' => 'confirmed',
                'payment_schedule' => json_encode([
                    ['milestone' => 'Booking Token (Paid)', 'percentage' => 10, 'amount' => 885000, 'dueDate' => '15 Sep 2026', 'status' => 'paid'],
                    ['milestone' => 'Execution of Agreement for Sale', 'percentage' => 15, 'amount' => 1327500, 'dueDate' => '15 Nov 2026', 'status' => 'upcoming'],
                    ['milestone' => 'Completion of Plinth Beam', 'percentage' => 20, 'amount' => 1770000, 'dueDate' => '15 Jan 2027', 'status' => 'pending'],
                    ['milestone' => 'Completion of Ground Floor Slab', 'percentage' => 25, 'amount' => 2212500, 'dueDate' => '15 Mar 2027', 'status' => 'pending'],
                    ['milestone' => 'Handover & Notice of Possession', 'percentage' => 30, 'amount' => 2655000, 'dueDate' => '30 Jun 2027', 'status' => 'pending']
                ]),
                'cost_breakdown' => json_encode([
                    'basePrice' => 8500000,
                    'idcEdc' => 340000,
                    'clubMembership' => 75000,
                    'parking' => 150000,
                    'gst' => 442000,
                    'registration' => 510000,
                    'total' => 10017000
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'booking_id' => 'KED-2026-5541',
                'unit_code' => 'KED-Plot12',
                'unit_number' => 'Plot 12',
                'project_slug' => 'kedias-landmark',
                'project_name' => "Kedia's Landmark",
                'customer_name' => 'Mrs. Meenakshi Agarwal',
                'customer_email' => 'meenakshi.agarwal@example.com',
                'customer_phone' => '+91 94140 88231',
                'customer_pan' => 'FGHIJ5678K',
                'customer_aadhar' => 'XXXX-XXXX-4521',
                'address' => 'B-18, Tonk Road, Jaipur',
                'booking_date' => '2026-10-02 14:15:00',
                'token_amount_paid' => 50000,
                'total_unit_amount' => 3105000,
                'payment_method' => 'NetBanking',
                'payment_reference' => 'NET/HDFC/20261002/99120',
                'status' => 'allotted',
                'payment_schedule' => json_encode([
                    ['milestone' => 'Booking Token (Paid)', 'percentage' => 15, 'amount' => 465750, 'dueDate' => '02 Oct 2026', 'status' => 'paid'],
                    ['milestone' => 'Agreement Signing (30 Days)', 'percentage' => 35, 'amount' => 1086750, 'dueDate' => '02 Nov 2026', 'status' => 'upcoming'],
                    ['milestone' => 'Registry & Plot Handover', 'percentage' => 50, 'amount' => 1552500, 'dueDate' => '15 Dec 2026', 'status' => 'pending']
                ]),
                'cost_breakdown' => json_encode([
                    'basePrice' => 2850000,
                    'idcEdc' => 114000,
                    'clubMembership' => 35000,
                    'parking' => 0,
                    'gst' => 0,
                    'registration' => 171000,
                    'total' => 3170000
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($bookings as $b) {
            DB::table('bookings')->updateOrInsert(['booking_id' => $b['booking_id']], $b);
        }

        // Link the seeded bookings to their units by project + code.
        DB::statement('
            UPDATE bookings
            SET unit_id = (
                SELECT units.id FROM units
                WHERE units.project_slug = bookings.project_slug
                  AND units.unit_code = bookings.unit_code
                LIMIT 1
            )
            WHERE unit_id IS NULL
        ');

        // A booked unit can't still be listed as available.
        DB::table('units')
            ->whereIn('id', DB::table('bookings')->whereNotNull('unit_id')->pluck('unit_id'))
            ->where('status', 'available')
            ->update(['status' => 'on_hold']);
    }
}
