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
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public static function routeName(){
        return Str::snake("Attachment");
    }
     public function __construct(Request $request)
    {
        // dd($request);
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
        if (isset($data['files']) && count($data['files']) > 0) {
            $attachableType = $data['attachable_type'];
            // $attachableId = $data['attachable_id'];
            $attachableId = 1;
            $attachamentType = $data['attachment_type'];

            Attachment::where('attachable_type', $attachableType)
            ->where('attachable_id', $attachableId)
            ->where('attachment_type', $attachamentType)
            ->each(function ($attachment) {
                // Delete the file from storage
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete(); // Remove the record from the database
            });
            // dd($data['files']);
            foreach ($data['files'] as $file) {
                // Store the file in the storage/app/public/attachments directory
                $filePath = $file->store('attachments', 'public');
    
                // Get file details
                $fileExtension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                $fileName = $file->getClientOriginalName();
    
                // Create the attachment record
                Attachment::create([
                    'attachable_type' => $attachableType,
                    'attachable_id' => $attachableId,
                    'attachment_type' => $data['attachment_type'] ?? 1, // Default to 1 if not provided
                    'user_id' => auth()->id() ?? null, // Assuming user_id should be the current authenticated user
                    'filename' => $fileName,
                    'path' => $filePath,
                    'file_extension' => $fileExtension,
                    'filesize' => $fileSize,
                ]);
            }
            return back()->with('res', [
                'message' => __('Attachments saved successfully'),
                'type' => 'success',
            ]);
        }
        return back()->withErrors(['error' => 'No files provided'])->withInput();
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
