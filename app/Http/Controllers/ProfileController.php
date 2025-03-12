<?php

namespace App\Http\Controllers;

use App\Models\ProposalBeneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalBeneficiaryRequest;
use App\Http\Requests\UpdateProposalBeneficiaryRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class ProfileController extends Controller
{
    public static function routeName(){
        return Str::snake("ProposalBeneficiary");
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
        
        return Inertia::render(Str::studly("ProposalBeneficiary").'/Index', [
            "headers" => ProposalBeneficiary::headers(),
            "items" => ProposalBeneficiary::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("ProposalBeneficiary").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposalBeneficiaryRequest $request)
    {
        $data = $request->validated();
        ProposalBeneficiary::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('ProposalBeneficiary Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(ProposalBeneficiary $proposalBeneficiary)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProposalBeneficiary $proposalBeneficiary)
    {
        return Inertia::render(Str::studly("ProposalBeneficiary").'/Update', [
            //'options' => $regions,
            'proposalBeneficiary' => $proposalBeneficiary->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalBeneficiaryRequest $request, ProposalBeneficiary $proposalBeneficiary)
    {
        $validated = $request->validated();
        
        $proposalBeneficiary->update($validated);
        return back()->with('res', ['message' => __('ProposalBeneficiary Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposalBeneficiary $proposalBeneficiary)
    {
        $proposalBeneficiary->delete();
        return back()->with('res', ['message' => __('ProposalBeneficiary Deleted Seccessfully'), 'type' => 'success']);
    }
}
