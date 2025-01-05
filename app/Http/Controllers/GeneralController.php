<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class GeneralController extends Controller
{
    public function changeLanguage($locale)
    {
        // dd($locale);
        try {
            // dd("fgfg");
            if (array_key_exists($locale,  config('locale.languages'))) {
                Cookie::queue(Cookie::make('locale', $locale, 52560000, null, null, false, false));
                Cookie::queue('test', 'Hello,World!');
                App::setLocale($locale);
                return redirect()->back();
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }
}
