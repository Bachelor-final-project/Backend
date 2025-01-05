<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    // public function __construct() {
    //     parent::__construct();
    //     // dd("EncryptCookie");
    // }
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        'locale',
        'laravel_session'
    ];
}
