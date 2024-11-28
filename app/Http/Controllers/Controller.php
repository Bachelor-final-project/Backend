<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

abstract class Controller
{
    public $user;

    public function __construct(Request $request)
    {
        if (auth('web')->user())
            $this->user = auth('web')->user();
    }
}
