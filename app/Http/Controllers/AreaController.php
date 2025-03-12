<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class AreaController extends Controller
{
    public static function routeName(){
        return Str::snake("Area");
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
        
        return Inertia::render(Str::studly("Area").'/Index', [
            "headers" => Area::headers(),
            "items" => Area::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Area").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAreaRequest $request)
    {
        $data = $request->validated();
        Area::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Area Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Area $area)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return Inertia::render(Str::studly("Area").'/Update', [
            //'options' => $regions,
            'area' => $area->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAreaRequest $request, Area $area)
    {
        $validated = $request->validated();
        
        $area->update($validated);
        return back()->with('res', ['message' => __('Area Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return back()->with('res', ['message' => __('Area Deleted Seccessfully'), 'type' => 'success']);
    }
}
