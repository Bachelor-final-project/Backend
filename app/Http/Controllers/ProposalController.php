<?php

namespace App\Http\Controllers;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProposalController extends Controller
{

    public static function routeName(){
        return Str::snake("Proposal");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return ProposalResource::collection(Proposal::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Proposal::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Proposal::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposal = Proposal::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposal->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalResource($proposal);
    }
    public function show(Request $request,Proposal $proposal)
    {
        if(!$this->user->is_permitted_to('view',Proposal::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new ProposalResource($proposal);
    }
    public function update(Request $request, Proposal $proposal)
    {
        if(!$this->user->is_permitted_to('update',Proposal::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Proposal::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $proposal->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $proposal->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new ProposalResource($proposal);
    }
    public function destroy(Request $request,Proposal $proposal)
    {
        if(!$this->user->is_permitted_to('delete',Proposal::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $proposal->delete();

        return new ProposalResource($proposal);
    }
}
