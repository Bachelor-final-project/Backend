<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class BeneficiaryController extends Controller
{
    public static function routeName(){
        return Str::snake("Beneficiary");
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
        
        return Inertia::render(Str::studly("Beneficiary").'/Index', [
            "headers" => Beneficiary::headers(),
            "items" => Beneficiary::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Beneficiary").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeneficiaryRequest $request)
    {
        $data = $request->validated();
        Beneficiary::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Beneficiary Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Beneficiary $beneficiary)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beneficiary $beneficiary)
    {
        return Inertia::render(Str::studly("Beneficiary").'/Update', [
            //'options' => $regions,
            'beneficiary' => $beneficiary->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $validated = $request->validated();
        
        $beneficiary->update($validated);
        return back()->with('res', ['message' => __('Beneficiary Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();
        return back()->with('res', ['message' => __('Beneficiary Deleted Seccessfully'), 'type' => 'success']);
    }
}
