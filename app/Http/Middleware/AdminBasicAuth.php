<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the admin console with HTTP Basic Auth.
 * Credentials come from ADMIN_USER / ADMIN_PASSWORD in .env.
 * If no password is configured, the admin console is closed entirely.
 */
class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) config('app.admin.user');
        $password = (string) config('app.admin.password');

        if ($user === '' || $password === '') {
            abort(503, 'Admin access is not configured. Set ADMIN_USER and ADMIN_PASSWORD in .env.');
        }

        $validUser = hash_equals($user, (string) $request->getUser());
        $validPassword = hash_equals($password, (string) $request->getPassword());

        if (!$validUser || !$validPassword) {
            return response('Authentication required.', 401, [
                'WWW-Authenticate' => 'Basic realm="Kedia Admin", charset="UTF-8"',
            ]);
        }

        return $next($request);
    }
}
