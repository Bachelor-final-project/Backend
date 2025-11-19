<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Pagination\LengthAwarePaginator;

class WarehouseController extends Controller
{
    public static function routeName(){
        return Str::snake("Warehouse");
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
        
        return Inertia::render(Str::studly("Warehouse").'/Index', [
            "headers" => Warehouse::headers(),
            "items" => Warehouse::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function warehouse_items(Request $request)
    {

        $warehouse_id = $request?->warehouse_id ?? 0;
        $item_id = $request?->item_id ?? 0;
        $perPage = $request->input('per_page', 10); // Items per page (default 10)
        $page = $request->input('page', 1); // Current page (default 1)

        // Execute the query
        $results = Warehouse::getWarehouseItems($warehouse_id, $item_id);
        $resultsCollection = collect($results);
        $paginatedResults = new LengthAwarePaginator(
            $resultsCollection->forPage($page, $perPage), // Items for the current page
            $resultsCollection->count(), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Pagination links
        );
        // dd($paginatedResults);
        // return $result;
        return Inertia::render(Str::studly("Warehouse").'/Items', [
            "headers" => Warehouse::availableItemsHeaders(),
            "items" => $paginatedResults,
            "products" => Item::select('id', 'name')->get(),
            'warehouses' => Warehouse::select('id', 'name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Warehouse").'/Create', [
            'status_options' => Warehouse::statuses()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request)
    {
        $data = $request->validated();
        Warehouse::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Warehouse Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Warehouse $warehouse)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return Inertia::render(Str::studly("Warehouse").'/Edit', [
            'status_options' => Warehouse::statuses(),
            'warehouse' => $warehouse->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $validated = $request->validated();
        
        $warehouse->update($validated);
        return back()->with('res', ['message' => __('Warehouse Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return back()->with('res', ['message' => __('Warehouse Deleted Seccessfully'), 'type' => 'success']);
    }
}
