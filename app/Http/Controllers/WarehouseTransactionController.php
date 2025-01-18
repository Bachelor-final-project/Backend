<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTransaction;
use App\Models\Warehouse;
use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseTransactionRequest;
use App\Http\Requests\UpdateWarehouseTransactionRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class WarehouseTransactionController extends Controller
{
    public static function routeName(){
        return Str::snake("WarehouseTransaction");
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
        
        return Inertia::render(Str::studly("WarehouseTransaction").'/Index', [
            "headers" => WarehouseTransaction::headers(),
            "items" => WarehouseTransaction::search($request)->sort($request)->paginate($this->pagination),
            "warehouses" => Warehouse::select('id', 'name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("WarehouseTransaction").'/Create', [
            "items" => Item::select('id', 'name')->get(),
            "warehouses" => Warehouse::select('id', 'name')->get(),
            "transaction_types" => WarehouseTransaction::transactionTypes(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseTransactionRequest $request)
    {
        $data = $request->validated();
        WarehouseTransaction::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('WarehouseTransaction Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(WarehouseTransaction $warehouseTransaction)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseTransaction $warehouseTransaction)
    {
        return Inertia::render(Str::studly("WarehouseTransaction").'/Edit', [
            //'options' => $regions,
            'warehouse_transaction' => $warehouseTransaction->toArray(),
            "items" => Item::select('id', 'name')->get(),
            "warehouses" => Warehouse::select('id', 'name')->get(),
            "transaction_types" => WarehouseTransaction::transactionTypes(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseTransactionRequest $request, WarehouseTransaction $warehouseTransaction)
    {
        $validated = $request->validated();
        
        $warehouseTransaction->update($validated);
        return back()->with('res', ['message' => __('WarehouseTransaction Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseTransaction $warehouseTransaction)
    {
        $warehouseTransaction->delete();
        return back()->with('res', ['message' => __('WarehouseTransaction Deleted Seccessfully'), 'type' => 'success']);
    }
}
