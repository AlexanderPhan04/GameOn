<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switch(Request $request)
    {
        try {
            $locale = $request->input('locale', 'en');

            // Simple validation
            if (! in_array($locale, ['en', 'vi'], true)) {
                $locale = 'en';
            }

            // Store locale in session
            Session::put('locale', $locale);

            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => 'Language switched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error switching language: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current locale
     */
    public function current()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'available_locales' => ['en', 'vi'],
        ]);
    }
}
