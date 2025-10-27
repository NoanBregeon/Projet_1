<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Vérifier que la langue est supportée
        if (!in_array($locale, ['fr', 'en'])) {
            abort(400);
        }

        // Sauvegarder en session
        Session::put('locale', $locale);

        return redirect()->back();
    }
}
