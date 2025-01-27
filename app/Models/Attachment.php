<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Attachment extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    protected $appends = ['url'];
    public static $controllable = true;
    public function attachable()
    {
        return $this->morphTo();
    }
    public function getUrlAttribute()
    {
        return env('APP_URL') . Storage::url($this->path);
    }
    public static function storeAttachment($file, $attachableId, $attachableType, $attachamentType = 1) {
        Attachment::where('attachable_type', $attachableType)
        ->where('attachable_id', $attachableId)
        ->where('attachment_type', $attachamentType)
        ->each(function ($attachment) {
            // Delete the file from storage
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete(); // Remove the record from the database
        });
        // dd($data['files']);
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
            'attachment_type' => $attachamentType, // Default to 1 if not provided
            'user_id' => auth()->id() ?? null, // Assuming user_id should be the current authenticated user
            'filename' => $fileName,
            'path' => $filePath,
            'file_extension' => $fileExtension,
            'filesize' => $fileSize,
        ]);
    }

}
