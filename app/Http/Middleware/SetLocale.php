<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');
        // dd($request->route());
        Log::debug("Middleware triggered. Locale: {$locale}");

        if ($locale && array_key_exists($locale, config('app.locales'))) {
            App::setLocale($locale);
            Log::debug("Locale set to: " . App::getLocale());
        } else {
            Log::debug("Locale not set or not found in allowed locales.");
        }

        return $next($request);
    }
}

    // another way
    // public function handle(Request $request, Closure $next)
    // {
    //     // Check for locale in request
    //     $locale = $request->header('Accept-Language');
    //     if (!$locale) {
    //         $locale = 'en';  // Default locale
    //     }

    //     // Set the locale
    //     App::setLocale($locale);

    //     return $next($request);
    // }
// }
