<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalTypesRequest;
use App\Http\Requests\UpdateProposalTypesRequest;
use App\Models\ProposalTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class ProposalTypesController extends Controller
{
    public static function routeName(){
        return Str::snake("ProposalTypes");
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
        
        return Inertia::render(Str::studly("ProposalTypes").'/Index', [
            "headers" => ProposalTypes::headers(),
            "items" => ProposalTypes::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("ProposalTypes").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposalTypesRequest $request)
    {
        $data = $request->validated();
        ProposalTypes::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('ProposalTypes Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(ProposalTypes $proposalTypes)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProposalTypes $proposalTypes)
    {
        return Inertia::render(Str::studly("ProposalTypes").'/Update', [
            //'options' => $regions,
            'proposalTypes' => $proposalTypes->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalTypesRequest $request, ProposalTypes $proposalTypes)
    {
        $validated = $request->validated();
        
        $proposalTypes->update($validated);
        return back()->with('res', ['message' => __('ProposalTypes Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposalTypes $proposalTypes)
    {
        $proposalTypes->delete();
        return back()->with('res', ['message' => __('ProposalTypes Deleted Seccessfully'), 'type' => 'success']);
    }
}
