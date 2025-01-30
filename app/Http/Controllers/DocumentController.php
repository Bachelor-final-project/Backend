<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\Proposal;
use App\Models\Donor;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class DocumentController extends Controller
{
    public static function routeName(){
        return Str::snake("Document");
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
        
        return Inertia::render(Str::studly("Document").'/Index', [
            "headers" => Document::headers(),
            'currencies' => Currency::get(),
            "items" => Document::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd(Proposal::where('status','=', Proposal::STATUSES['completed'])->get(),);
         return Inertia::render(Str::studly("Document").'/Create', [
            // 'proposals' => Proposal::select('id', 'name')->where('status', '=', Proposal::STATUSES['completed'])->get(),
            'proposals' => Proposal::where('status','=', Proposal::STATUSES['completed'])->get(),
            'donors' => Donor::select('id', 'name')->get(),
            'currencies' => Currency::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        Document::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Document Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Document $document)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return Inertia::render(Str::studly("Document").'/Edit', [
            'proposals' => Proposal::where('status','=', Proposal::STATUSES['completed'])->get(),
            'donors' => Donor::select('id', 'name')->get(),
            'currencies' => Currency::all(),
            'document' => $document->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $validated = $request->validated();
        
        $document->update($validated);
        return back()->with('res', ['message' => __('Document Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return back()->with('res', ['message' => __('Document Deleted Seccessfully'), 'type' => 'success']);
    }
}
