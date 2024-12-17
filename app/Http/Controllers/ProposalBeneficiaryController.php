<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalBeneficiaryRequest;
use App\Http\Requests\UpdateProposalBeneficiaryRequest;
use App\Http\Resources\ProposalBeneficiaryResource;
use App\Models\ProposalBeneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalBeneficiaryController extends Controller
{

    public static function routeName()
    {
        return Str::snake("ProposalBeneficiary");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return ProposalBeneficiaryResource::collection(ProposalBeneficiary::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }
    public function store(StoreProposalBeneficiaryRequest $request)
    {
        $proposalBeneficiary = ProposalBeneficiary::create($request->validated());

        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function show(Request $request, ProposalBeneficiary $proposalBeneficiary)
    {
        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function update(UpdateProposalBeneficiaryRequest $request, ProposalBeneficiary $proposalBeneficiary)
    {
        $proposalBeneficiary->update($request->validated());

        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function destroy(Request $request, ProposalBeneficiary $proposalBeneficiary)
    {
        $proposalBeneficiary->delete();

        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
}
