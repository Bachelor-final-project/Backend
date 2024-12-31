<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class AttachmentController extends Controller
{
    public static function routeName(){
        return Str::snake("Attachment");
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
        
        return Inertia::render(Str::studly("Attachment").'/Index', [
            "headers" => Attachment::headers(),
            "items" => Attachment::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Attachment").'/Create', [
            // 'options' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request)
    {
        $data = $request->validated();
        Attachment::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Attachment Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Attachment $attachment)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        return Inertia::render(Str::studly("Attachment").'/Update', [
            //'options' => $regions,
            'attachment' => $attachment->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttachmentRequest $request, Attachment $attachment)
    {
        $validated = $request->validated();
        
        $attachment->update($validated);
        return back()->with('res', ['message' => __('Attachment Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return back()->with('res', ['message' => __('Attachment Deleted Seccessfully'), 'type' => 'success']);
    }
}
