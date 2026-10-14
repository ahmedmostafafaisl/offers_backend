<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Web default language is Arabic (visitors can still switch via /lang/{locale}).
        // Note: this middleware runs on the "web" group only, so API locale is unaffected.
        $locale = session('locale', 'ar');

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
