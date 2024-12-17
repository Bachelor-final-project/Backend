<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLogRequest;
use App\Http\Requests\UpdateLogRequest;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LogController extends Controller
{

    public static function routeName()
    {
        return Str::snake("Log");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return LogResource::collection(Log::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }
    public function store(StoreLogRequest $request)
    {
        $log = Log::create($request->validated());

        return new LogResource($log);
    }
    public function show(Request $request, Log $log)
    {
        return new LogResource($log);
    }
    public function update(UpdateLogRequest $request, Log $log)
    {
        $log->update($request->validated());

        return new LogResource($log);
    }
    public function destroy(Request $request, Log $log)
    {
        $log->delete();

        return new LogResource($log);
    }
}
