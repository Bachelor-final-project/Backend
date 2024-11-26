<?php

namespace App\Http\Controllers;
use App\Http\Resources\ProposalDetailResource;
use App\Models\ProposalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalDetailController extends Controller
{

    public static function routeName(){
        return Str::snake("ProposalDetail");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return ProposalDetailResource::collection(ProposalDetail::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',ProposalDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),ProposalDetail::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposalDetail = ProposalDetail::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposalDetail->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalDetailResource($proposalDetail);
    }
    public function show(Request $request,ProposalDetail $proposalDetail)
    {
        if(!$this->user->is_permitted_to('view',ProposalDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new ProposalDetailResource($proposalDetail);
    }
    public function update(Request $request, ProposalDetail $proposalDetail)
    {
        if(!$this->user->is_permitted_to('update',ProposalDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),ProposalDetail::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposalDetail->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposalDetail->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalDetailResource($proposalDetail);
    }
    public function destroy(Request $request,ProposalDetail $proposalDetail)
    {
        if(!$this->user->is_permitted_to('delete',ProposalDetail::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $proposalDetail->delete();

        return new ProposalDetailResource($proposalDetail);
    }
}
