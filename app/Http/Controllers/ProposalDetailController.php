<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalDetailRequest;
use App\Http\Requests\UpdateProposalDetailRequest;
use App\Http\Resources\ProposalDetailResource;
use App\Models\ProposalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalDetailController extends Controller
{

    public static function routeName()
    {
        return Str::snake("ProposalDetail");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return view("", [
            'headers' => ProposalDetail::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return ProposalDetailResource::collection(ProposalDetail::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("", [
            'data_to_send' => 'Hello, World!'
        ]);
    }

    public function store(StoreProposalDetailRequest $request)
    {
        $proposalDetail = ProposalDetail::create($request->validated());

        return new ProposalDetailResource($proposalDetail);
    }

    public function show(Request $request, ProposalDetail $proposalDetail)
    {
        return new ProposalDetailResource($proposalDetail);
    }

    public function edit()
    {
        return view("", [
            'data_to_send' => 'Hello, World!',
        ]);
    }

    public function update(UpdateProposalDetailRequest $request, ProposalDetail $proposalDetail)
    {
        $proposalDetail->update($request->validated());

        return new ProposalDetailResource($proposalDetail);
    }

    public function destroy(Request $request, ProposalDetail $proposalDetail)
    {
        $proposalDetail->delete();

        return new ProposalDetailResource($proposalDetail);
    }
}
