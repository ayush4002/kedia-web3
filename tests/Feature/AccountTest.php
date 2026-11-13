<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function signIn(string $phone): void
    {
        $this->post('/account/otp', ['phone' => $phone])->assertRedirect('/account');
        $otp = session('otp_demo');
        $this->assertMatchesRegularExpression('/^\d{6}$/', (string) $otp);
        $this->post('/account/verify', ['otp' => $otp])->assertRedirect('/account');
    }

    public function test_account_asks_for_sign_in_and_shows_no_bookings(): void
    {
        $booking = Booking::first();
        $this->get('/account')->assertOk()->assertSee('Sign in with your mobile')->assertDontSee($booking->booking_id);
    }

    public function test_otp_sign_in_shows_only_that_numbers_bookings(): void
    {
        $mine = Booking::first();
        $other = Booking::where('id', '!=', $mine->id)->first();

        $this->signIn($mine->customer_phone);

        $this->get('/account')->assertOk()
            ->assertSee($mine->booking_id)
            ->assertDontSee($other->booking_id)
            ->assertSee('signature=', false);
    }

    public function test_demo_otp_is_shown_outside_production_only(): void
    {
        $this->post('/account/otp', ['phone' => '9876543210']);
        $this->get('/account')->assertSee('Demo mode')->assertSee((string) session('otp_demo'));
    }

    public function test_wrong_otp_is_rejected_and_attempts_are_limited(): void
    {
        $this->post('/account/otp', ['phone' => '9876543210']);
        $real = session('otp_demo');
        $wrong = $real === '000000' ? '111111' : '000000';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/account/verify', ['otp' => $wrong])->assertSessionHasErrors('otp');
        }
        // After 5 wrong tries the code is discarded, so even the real one fails.
        $this->post('/account/verify', ['otp' => $real])->assertSessionHasErrors('otp');
        $this->get('/account')->assertSee('Sign in with your mobile');
    }

    public function test_sign_out(): void
    {
        $this->signIn(Booking::first()->customer_phone);
        $this->post('/account/logout')->assertRedirect('/account');
        $this->get('/account')->assertSee('Sign in with your mobile');
    }
}
