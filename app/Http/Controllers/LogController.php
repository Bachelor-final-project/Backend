<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogRequest;
use App\Http\Requests\UpdateLogRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class LogController extends Controller
{
    public static function routeName(){
        return Str::snake("Log");
    }
     public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        return Inertia::render(Str::studly("Log").'/Index', [
            "headers" => Log::headers(),
            "items" => Log::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Log").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLogRequest $request)
    {
        $data = $request->validated();
        Log::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Log Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Log $log)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        return Inertia::render(Str::studly("Log").'/Update', [
            //'options' => $regions,
            'log' => $log->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogRequest $request, Log $log)
    {
        $validated = $request->validated();
        
        $log->update($validated);
        return back()->with('res', ['message' => __('Log Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        $log->delete();
        return back()->with('res', ['message' => __('Log Deleted Seccessfully'), 'type' => 'success']);
    }
}
