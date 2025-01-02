<?php

namespace App\Http\Controllers;

use App\Models\WarehouseDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseDetailRequest;
use App\Http\Requests\UpdateWarehouseDetailRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class WarehouseDetailController extends Controller
{
    public static function routeName(){
        return Str::snake("WarehouseDetail");
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
        
        return Inertia::render(Str::studly("WarehouseDetail").'/Index', [
            "headers" => WarehouseDetail::headers(),
            "items" => WarehouseDetail::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("WarehouseDetail").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseDetailRequest $request)
    {
        $data = $request->validated();
        WarehouseDetail::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('WarehouseDetail Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(WarehouseDetail $warehouseDetail)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseDetail $warehouseDetail)
    {
        return Inertia::render(Str::studly("WarehouseDetail").'/Update', [
            //'options' => $regions,
            'warehouseDetail' => $warehouseDetail->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseDetailRequest $request, WarehouseDetail $warehouseDetail)
    {
        $validated = $request->validated();
        
        $warehouseDetail->update($validated);
        return back()->with('res', ['message' => __('WarehouseDetail Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseDetail $warehouseDetail)
    {
        $warehouseDetail->delete();
        return back()->with('res', ['message' => __('WarehouseDetail Deleted Seccessfully'), 'type' => 'success']);
    }
}
