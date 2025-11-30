<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramRouter;
use Illuminate\Http\Response;

class TelegramController extends Controller
{
    protected $router;
    
    public function __construct(TelegramRouter $router)
    {
        $this->router = $router;
    }
    
    public function webhook(Request $request)
    {
        try {
            $update = $request->all();
            $this->router->dispatch($update);
            return response('OK', Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Telegram webhook error: ' . $e->getMessage());
            return response('Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}