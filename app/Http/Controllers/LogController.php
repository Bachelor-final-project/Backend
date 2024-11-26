<?php

namespace App\Http\Controllers;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LogController extends Controller
{

    public static function routeName(){
        return Str::snake("Log");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return LogResource::collection(Log::search($request)->sort($request)->paginate((request('per_page')??request('itemsPerPage'))??15));
    }
    public function store(Request $request)
    {
        if(!$this->user->is_permitted_to('store',Log::class,$request))
            return response()->json(['message'=>'not_permitted'],422);

        $validator = Validator::make($request->all(),Log::createRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $log = Log::create($validator->validated());
        if ($request->translations) {
            foreach ($request->translations as $translation)
                $log->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new LogResource($log);
    }
    public function show(Request $request,Log $log)
    {
        if(!$this->user->is_permitted_to('view',Log::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        return new LogResource($log);
    }
    public function update(Request $request, Log $log)
    {
        if(!$this->user->is_permitted_to('update',Log::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $validator = Validator::make($request->all(),Log::updateRules($this->user));
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $log->update($validator->validated());
          if ($request->translations) {
            foreach ($request->translations as $translation)
                $log->setTranslation($translation['field'], $translation['locale'], $translation['value'])->save();
        }
        return new LogResource($log);
    }
    public function destroy(Request $request,Log $log)
    {
        if(!$this->user->is_permitted_to('delete',Log::class,$request))
            return response()->json(['message'=>'not_permitted'],422);
        $log->delete();

        return new LogResource($log);
    }
}
