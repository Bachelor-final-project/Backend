<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalController extends Controller
{

    public static function routeName()
    {
        return Str::snake("Proposal");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return view("", [
            'headers' => Proposal::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return ProposalResource::collection(Proposal::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("", [
            'data_to_send' => 'Hello, World!'
        ]);
    }

    public function store(StoreProposalRequest $request)
    {
        $proposal = Proposal::create($request->validated());

        return new ProposalResource($proposal);
    }

    public function show(Request $request, Proposal $proposal)
    {
        return new ProposalResource($proposal);
    }

    public function edit()
    {
        return view("", [
            'data_to_send' => 'Hello, World!',
        ]);
    }

    public function update(UpdateProposalRequest $request, Proposal $proposal)
    {
        $proposal->update($request->validated());

        return new ProposalResource($proposal);
    }

    public function destroy(Request $request, Proposal $proposal)
    {
        $proposal->delete();

        return new ProposalResource($proposal);
    }
}
