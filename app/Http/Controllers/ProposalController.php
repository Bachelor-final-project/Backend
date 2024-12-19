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
        return view("dashboard." . $this->routeName() . ".index", [
            'headers' => $this->getModelInstance()::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return ProposalResource::collection(Proposal::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("dashboard." . $this->routeName() . ".create", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $this->getModelInstance()
        ]);
    }

    public function store(StoreProposalRequest $request)
    {
        $proposal = Proposal::create($request->validated());

        return new ProposalResource($proposal);
    }

    public function show(Request $request, Proposal $proposal)
    {
        return view("dashboard." . $this->routeName() . ".show", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $proposal
        ]);
    }

    public function edit(Proposal $proposal)
    {
        return view("dashboard." . $this->routeName() . ".edit", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $proposal
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
