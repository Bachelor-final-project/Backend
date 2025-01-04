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
        try {
            if (array_key_exists($locale,  config('locale.languages'))) {
                Cookie::queue('locale', $locale);
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
