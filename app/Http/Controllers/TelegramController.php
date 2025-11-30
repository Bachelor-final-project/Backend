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
        \Log::info('Telegram webhook received', ['data' => $request->all()]);
        
        try {
            $update = $request->all();
            \Log::info('Processing Telegram update', ['update' => $update]);
            
            $this->router->dispatch($update);
            
            \Log::info('Telegram update processed successfully');
            return response('OK', Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Telegram webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response('Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}