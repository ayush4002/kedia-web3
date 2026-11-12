<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_no_booking_is_shown_by_default(): void
    {
        $booking = Booking::first();
        $this->get('/dashboard')->assertOk()->assertSee('Find your booking')->assertDontSee($booking->booking_id);
    }

    public function test_booking_requires_matching_id_and_phone(): void
    {
        $booking = Booking::first();
        $digits = substr(preg_replace('/\D+/', '', $booking->customer_phone), -10);

        $this->get('/dashboard?' . http_build_query(['booking_id' => $booking->booking_id, 'phone' => '9000000000']))
            ->assertSee('No booking matches those details')
            ->assertDontSee($booking->customer_name);

        $this->get('/dashboard?' . http_build_query(['booking_id' => strtolower($booking->booking_id), 'phone' => '+91 ' . $digits]))
            ->assertSee($booking->booking_id)
            ->assertSee('signature=', false);
    }
}
