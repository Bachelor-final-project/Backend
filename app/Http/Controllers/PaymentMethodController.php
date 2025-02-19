<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class PaymentMethodController extends Controller
{
    public static function routeName(){
        return Str::snake("PaymentMethod");
    }
     public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        return Inertia::render(Str::studly("PaymentMethod").'/Index', [
            "headers" => PaymentMethod::headers(),
            "items" => PaymentMethod::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("PaymentMethod").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $data = $request->validated();
        PaymentMethod::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('PaymentMethod Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(PaymentMethod $paymentMethod)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return Inertia::render(Str::studly("PaymentMethod").'/Update', [
            //'options' => $regions,
            'paymentMethod' => $paymentMethod->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validated();
        
        $paymentMethod->update($validated);
        return back()->with('res', ['message' => __('PaymentMethod Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return back()->with('res', ['message' => __('PaymentMethod Deleted Seccessfully'), 'type' => 'success']);
    }
}
