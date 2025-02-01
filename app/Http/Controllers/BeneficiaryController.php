<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Warehouse;
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
        $warehouses = Warehouse::all();
         return Inertia::render(Str::studly("Beneficiary").'/Create', [
            'warehouses' => $warehouses,
            'social_statuses' => Beneficiary::socialStatuses(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeneficiaryRequest $request)
    {
        $data = $request->validated();
        $f_id = $data['father_national_id'];
        $isValid = Beneficiary::isValidFatherNationalId($data['national_id'], $data['father_national_id']);
        if(!$isValid) {
            return response()->json(['errors' => ['father_national_id' => 'This Father National ID Create Circualr Dependency']]);
        }
        $data = $request->validated();
        $national_id = $data['national_id'];
        $ben = Beneficiary::withTrashed()->where('national_id', $national_id)->first();
        if ($ben?->id) {
            $ben->update(array_merge($data, ['deleted_at' => null]));
        } else {
            Beneficiary::create($data);
        }
        // if($f_id) {
        //     $b = Beneficiary::where('national_id', '=', $f_id)->first();
        //     $father_id = $b?->id ?? null;
        // }
        // unset($data['father_national_id']);
        // $data['father_id'] = $father_id;
        // dd($data);
        
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
        $warehouses = Warehouse::all();
        return Inertia::render(Str::studly("Beneficiary").'/Edit', [
            //'options' => $regions,
            'beneficiary' => $beneficiary->toArray(),
            'warehouses' => $warehouses,
            'social_statuses' => Beneficiary::socialStatuses(),
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
