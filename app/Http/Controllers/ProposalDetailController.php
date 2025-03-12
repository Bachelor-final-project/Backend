<?php

namespace App\Http\Controllers;

use App\Models\ProposalDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalDetailRequest;
use App\Http\Requests\UpdateProposalDetailRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class ProposalDetailController extends Controller
{
    public static function routeName(){
        return Str::snake("ProposalDetail");
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
        
        return Inertia::render(Str::studly("ProposalDetail").'/Index', [
            "headers" => ProposalDetail::headers(),
            "items" => ProposalDetail::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("ProposalDetail").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposalDetailRequest $request)
    {
        $data = $request->validated();
        ProposalDetail::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('ProposalDetail Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(ProposalDetail $proposalDetail)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProposalDetail $proposalDetail)
    {
        return Inertia::render(Str::studly("ProposalDetail").'/Update', [
            //'options' => $regions,
            'proposalDetail' => $proposalDetail->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalDetailRequest $request, ProposalDetail $proposalDetail)
    {
        $validated = $request->validated();
        
        $proposalDetail->update($validated);
        return back()->with('res', ['message' => __('ProposalDetail Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposalDetail $proposalDetail)
    {
        $proposalDetail->delete();
        return back()->with('res', ['message' => __('ProposalDetail Deleted Seccessfully'), 'type' => 'success']);
    }
}
