<?php

namespace App\Http\Controllers;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{

    public static function routeName(){
        return Str::snake("Attachment");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return AttachmentResource::collection(Attachment::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Attachment::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Attachment::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $attachment = Attachment::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $attachment->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new AttachmentResource($attachment);
    }
    public function show(Request $request,Attachment $attachment)
    {
        if(!$this->user->is_permitted_to('view',Attachment::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new AttachmentResource($attachment);
    }
    public function update(Request $request, Attachment $attachment)
    {
        if(!$this->user->is_permitted_to('update',Attachment::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Attachment::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $attachment->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $attachment->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new AttachmentResource($attachment);
    }
    public function destroy(Request $request,Attachment $attachment)
    {
        if(!$this->user->is_permitted_to('delete',Attachment::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $attachment->delete();

        return new AttachmentResource($attachment);
    }
}
