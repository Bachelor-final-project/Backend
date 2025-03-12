<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class ItemController extends Controller
{
    public static function routeName(){
        return Str::snake("Item");
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
        
        return Inertia::render(Str::studly("Item").'/Index', [
            "headers" => Item::headers(),
            "items" => Item::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {  
        $units = Unit::all();
        return Inertia::render(Str::studly("Item").'/Create', [
            'units' => $units
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        Item::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Item Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Item $item)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $units = Unit::all();
        return Inertia::render(Str::studly("Item").'/Edit', [
            'units' => $units,
            'item' => $item->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        
        $item->update($validated);
        return back()->with('res', ['message' => __('Item Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('res', ['message' => __('Item Deleted Seccessfully'), 'type' => 'success']);
    }
}
