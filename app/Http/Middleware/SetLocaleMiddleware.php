<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', Session::get('app_locale'));
        // Always set it, so a locale from an earlier request in the same process never carries over.
        App::setLocale(in_array($locale, ['en', 'hi'], true) ? $locale : config('app.locale', 'en'));

        return $next($request);
    }
}
