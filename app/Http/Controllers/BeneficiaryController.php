<?php

namespace App\Http\Controllers;
use App\Http\Resources\BeneficiaryResource;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeneficiaryController extends Controller
{

    public static function routeName(){
        return Str::snake("Beneficiary");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return BeneficiaryResource::collection(Beneficiary::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Beneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Beneficiary::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $beneficiary = Beneficiary::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $beneficiary->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new BeneficiaryResource($beneficiary);
    }
    public function show(Request $request,Beneficiary $beneficiary)
    {
        if(!$this->user->is_permitted_to('view',Beneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new BeneficiaryResource($beneficiary);
    }
    public function update(Request $request, Beneficiary $beneficiary)
    {
        if(!$this->user->is_permitted_to('update',Beneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Beneficiary::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $beneficiary->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $beneficiary->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new BeneficiaryResource($beneficiary);
    }
    public function destroy(Request $request,Beneficiary $beneficiary)
    {
        if(!$this->user->is_permitted_to('delete',Beneficiary::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $beneficiary->delete();

        return new BeneficiaryResource($beneficiary);
    }
}
