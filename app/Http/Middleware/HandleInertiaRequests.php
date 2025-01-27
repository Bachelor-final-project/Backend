<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Tightenco\Ziggy\Ziggy;
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
      
            'locale' => Cookie::get('locale') ?? app()->getLocale(),
            'app' => [
                'recaptcha_key' => config('services.recaptcha.key', "meow"),
            ],
            'flash' => function () {
                $res = null;
                if (Session::has('res')) {
                    $res =  session('res');
                    Session::forget('res');
                }

                return  $res;
            },
        ]);
    }
}
