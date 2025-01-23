<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class TenantController extends Controller
{
    public static function routeName(){
        return Str::snake("Tenant");
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
        
        return Inertia::render(Str::studly("Tenant").'/Index', [
            "headers" => Tenant::headers(),
            "items" => Tenant::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Tenant").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        $data = $request->validated();
        Tenant::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Tenant Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Tenant $tenant)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return Inertia::render(Str::studly("Tenant").'/Update', [
            //'options' => $regions,
            'tenant' => $tenant->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $validated = $request->validated();
        
        $tenant->update($validated);
        return back()->with('res', ['message' => __('Tenant Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return back()->with('res', ['message' => __('Tenant Deleted Seccessfully'), 'type' => 'success']);
    }
}
