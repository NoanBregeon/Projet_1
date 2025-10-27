<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Toujours utiliser le français par défaut
        $locale = 'fr';

        // Seulement changer si explicitement demandé en session
        if (Session::has('locale') && Session::get('locale') === 'en') {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
