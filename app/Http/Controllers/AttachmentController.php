<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{

    public static function routeName()
    {
        return Str::snake("Attachment");
    }
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    public function index(Request $request)
    {
        return AttachmentResource::collection(Attachment::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }
    public function store(StoreAttachmentRequest $request)
    {
        $attachment = Attachment::create($request->validated());

        return new AttachmentResource($attachment);
    }
    public function show(Request $request, Attachment $attachment)
    {
        return new AttachmentResource($attachment);
    }
    public function update(UpdateAttachmentRequest $request, Attachment $attachment)
    {
        $attachment->update($request->validated());

        return new AttachmentResource($attachment);
    }
    public function destroy(Request $request, Attachment $attachment)
    {
        $attachment->delete();

        return new AttachmentResource($attachment);
    }
}
