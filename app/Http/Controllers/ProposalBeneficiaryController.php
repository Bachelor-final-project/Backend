<?php

namespace App\Http\Controllers;
use App\Http\Resources\ProposalBeneficiaryResource;
use App\Models\ProposalBeneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalBeneficiaryController extends Controller
{

    public static function routeName(){
        return Str::snake("ProposalBeneficiary");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return ProposalBeneficiaryResource::collection(ProposalBeneficiary::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',ProposalBeneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),ProposalBeneficiary::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposalBeneficiary = ProposalBeneficiary::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposalBeneficiary->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function show(Request $request,ProposalBeneficiary $proposalBeneficiary)
    {
        if(!$this->user->is_permitted_to('view',ProposalBeneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function update(Request $request, ProposalBeneficiary $proposalBeneficiary)
    {
        if(!$this->user->is_permitted_to('update',ProposalBeneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),ProposalBeneficiary::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposalBeneficiary->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposalBeneficiary->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
    public function destroy(Request $request,ProposalBeneficiary $proposalBeneficiary)
    {
        if(!$this->user->is_permitted_to('delete',ProposalBeneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $proposalBeneficiary->delete();

        return new ProposalBeneficiaryResource($proposalBeneficiary);
    }
}
