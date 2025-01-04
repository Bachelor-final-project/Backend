<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class CurrencyController extends Controller
{
    public static function routeName(){
        return Str::snake("Currency");
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
        
        return Inertia::render(Str::studly("Currency").'/Index', [
            "headers" => Currency::headers(),
            "items" => Currency::search($request)->sort($request)->paginate($this->pagination),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Currency").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request)
    {
        $data = $request->validated();
        Currency::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Currency Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Currency $currency)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return Inertia::render(Str::studly("Currency").'/Edit', [
            //'options' => $regions,
            'currency' => $currency->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $validated = $request->validated();
        
        $currency->update($validated);
        return back()->with('res', ['message' => __('Currency Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return back()->with('res', ['message' => __('Currency Deleted Seccessfully'), 'type' => 'success']);
    }
}
