<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Project;
use Illuminate\Http\Request;

/**
 * Customer account with mobile OTP sign-in.
 *
 * No SMS gateway is connected yet, so outside production the OTP is shown on
 * screen ("demo mode"). In production it is never shown; wire an SMS provider
 * into sendOtp() before launch.
 */
class AccountController extends Controller
{
    private const OTP_TTL_MINUTES = 10;
    private const OTP_MAX_ATTEMPTS = 5;

    public function index(Request $request)
    {
        $phone = $request->session()->get('customer_phone');

        if (!$phone) {
            return view('pages.account.login', [
                'pending' => $request->session()->get('otp_pending'),
                'demoOtp' => $this->demoMode() ? $request->session()->get('otp_demo') : null,
            ]);
        }

        $bookings = Booking::with(['unit', 'project'])->forPhone($phone)
            ->reject(fn ($b) => $b->status === Booking::STATUS_AWAITING_PAYMENT && $b->created_at->lt(now()->subDay()))
            ->sortByDesc('created_at')
            ->values();

        $active = $bookings->reject(fn ($b) => in_array($b->status, ['cancelled', 'refunded'], true));

        // Upcoming milestone payments across active bookings
        $reminders = $active->flatMap(fn ($b) => collect($b->payment_schedule ?? [])
            ->filter(fn ($m) => ($m['status'] ?? '') === 'Upcoming')
            ->map(fn ($m) => $m + ['booking' => $b]))
            ->sortBy('due_date')
            ->values();

        $projects = Project::whereIn('slug', $active->pluck('project_slug')->unique())->get()->keyBy('slug');

        return view('pages.account.dashboard', [
            'phone' => $phone,
            'name' => $bookings->first()->customer_name ?? null,
            'bookings' => $bookings,
            'active' => $active,
            'reminders' => $reminders,
            'projects' => $projects,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,20}$/'],
        ], ['phone.regex' => __('Enter a valid mobile number.')]);

        $phone = Booking::normalisePhone($data['phone']);
        if (strlen($phone) !== 10) {
            return back()->withErrors(['phone' => __('Enter a valid 10-digit mobile number.')])->withInput();
        }

        $otp = (string) random_int(100000, 999999);

        $request->session()->put('otp_pending', [
            'phone' => $phone,
            'hash' => hash_hmac('sha256', $otp, (string) config('app.key')),
            'expires' => now()->addMinutes(self::OTP_TTL_MINUTES)->timestamp,
            'attempts' => 0,
        ]);

        if ($this->demoMode()) {
            $request->session()->put('otp_demo', $otp);
        }
        // Production: send $otp by SMS here.

        return redirect()->route('account')->with('success', __('We sent a 6-digit code to your mobile number.'));
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $pending = $request->session()->get('otp_pending');
        if (!$pending || $pending['expires'] < now()->timestamp) {
            $request->session()->forget(['otp_pending', 'otp_demo']);
            return redirect()->route('account')->withErrors(['otp' => __('That code has expired. Request a new one.')]);
        }

        $pending['attempts']++;
        $valid = hash_equals($pending['hash'], hash_hmac('sha256', (string) $request->input('otp'), (string) config('app.key')));

        if (!$valid) {
            if ($pending['attempts'] >= self::OTP_MAX_ATTEMPTS) {
                $request->session()->forget(['otp_pending', 'otp_demo']);
                return redirect()->route('account')->withErrors(['otp' => __('Too many wrong attempts. Request a new code.')]);
            }
            $request->session()->put('otp_pending', $pending);
            return redirect()->route('account')->withErrors(['otp' => __('That code is not right. Please try again.')]);
        }

        $request->session()->forget(['otp_pending', 'otp_demo']);
        $request->session()->regenerate();
        $request->session()->put('customer_phone', $pending['phone']);

        return redirect()->route('account')->with('success', __('You are signed in.'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['customer_phone', 'otp_pending', 'otp_demo']);
        $request->session()->regenerate();

        return redirect()->route('account')->with('success', __('You are signed out.'));
    }

    private function demoMode(): bool
    {
        return !app()->environment('production');
    }
}
