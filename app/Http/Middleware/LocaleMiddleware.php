<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class LocaleMiddleware
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(Cookie::get('locale'));
        if (config('locale.status')) {

            if (Cookie::get('locale')) {
                if (!in_array(Cookie::get('locale'), ['en', 'ar'])) {
                    abort(400);
                }
                app()->setLocale(request()->cookie('locale'));
                // dd("Hell");
            } else {
                app()->setLocale('ar');
                Cookie::queue(Cookie::make('locale', 'ar', 52560000, null, null, false, false));
                Cookie::queue('test', 'Hello,World!');
                // $user_lang = preg_split('/[,;]/', $request->server('HTTP_ACCEPT_LANGUAGE'));
                // foreach ($user_lang as $lang) {
                //     if (array_key_exists($lang, config('locale.languages'))) {
                //         app()->setLocale(LC_TIME, config('locale.languages')[$lang][2]);
                //         Carbon::setLocale(config('locale.languages')[$lang][0]);
                //         if (config('locale.languages')[$lang][2]) {
                //             \Session(['lang-rtl' => true]);
                //         } else {
                //             Session::forget('lang-rtl');
                //         }
                //         break;
                //     }
                // }
            }
        }
        return $next($request);
    }
}
