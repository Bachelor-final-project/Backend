<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class UnitController extends Controller
{
    public static function routeName(){
        return Str::snake("Unit");
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
        
        return Inertia::render(Str::studly("Unit").'/Index', [
            "headers" => Unit::headers(),
            "items" => Unit::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Unit").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();
        Unit::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Unit Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Unit $unit)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return Inertia::render(Str::studly("Unit").'/Edit', [
            //'options' => $regions,
            'unit' => $unit->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $validated = $request->validated();
        
        $unit->update($validated);
        return back()->with('res', ['message' => __('Unit Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('res', ['message' => __('Unit Deleted Seccessfully'), 'type' => 'success']);
    }
}
