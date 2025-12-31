<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Determine locale: session -> browser -> default
        $supportedLocales = config('app.supported_locales', ['en']);

        $locale = Session::get('locale');

        if (! $locale) {
            // Try to detect from browser Accept-Language header
            $browserLocale = $request->getPreferredLanguage($supportedLocales);
            $locale = $browserLocale ?: config('app.locale', 'en');
        }

        // Validate locale against supported list; fallback to English
        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
