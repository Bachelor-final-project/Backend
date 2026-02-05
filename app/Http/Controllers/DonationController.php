<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Http\Resources\DonationListResource;
use App\Http\Resources\ProposalListResource;
use App\Http\Resources\ShortDonorResource;
use App\Http\Resources\ShortProposalResource;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Currency;
use App\Models\Document;
use App\Models\PaymentMethod;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class DonationController extends Controller
{
    public static function routeName(){
        return Str::snake("Donation");
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
        $donations = Donation::query()
            ->with(['donor.country', 'payment_method', 'proposal', 'currency'])
            ->search($request)
            ->sort($request)
            ->paginate($request->per_page ?? $this->pagination);
        
        return Inertia::render(Str::studly("Donation").'/Index', [
            "headers" => Donation::headers(),
            'currencies' => Currency::get(),
            'proposals' => ShortProposalResource::collection(Proposal::select('id', 'title')->latest()->take(100)->get())->toArray($request),
            'statuses' => Donation::statuses(),
            'donors' => ShortDonorResource::collection(Donor::select('id', 'name')->get())->toArray($request),
            "items" => DonationListResource::collection($donations),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Donation").'/Create', [
            'status_options' => Donation::statuses(),
            'currencies' => Currency::all(),
            'proposals' => Proposal::where('status', '=', Proposal::STATUSES['donatable'])->select('id', 'title', 'min_documenting_amount')->get(),
            'payment_methods' => PaymentMethod::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonationRequest $request)
    {
        $data = $request->validated();
        $donorPhone = $data['phone'];
        unset($data['phone']);
        // dd($donorPhone);
        $data['donor_id'] = Donor::where('phone', '=', $donorPhone)->first()->id;
        $donation = Donation::create($data);
        // if($data['status'] && $data['status'] == 2){
            Document::updateOrCreateDocumentForDonation($donation);
        // }
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Donation Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Donation $donation)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        $donation->load(['donor.country', 'payment_method', 'proposal', 'currency']);
        // dd(new DonationListResource($donation) );
        return Inertia::render(Str::studly("Donation").'/Edit', [
            'status_options' => Donation::statuses(),
            'currencies' => Currency::all(),
            'proposals' => Proposal::where('status', '=', Proposal::STATUSES['donatable'])->select('id', 'title', 'min_documenting_amount')->get(),
            'payment_methods' => PaymentMethod::where('entity_id', $donation->proposal->entity_id)->get(),
            'donation' => (new DonationListResource($donation))->toArray(request()) 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        $validated = $request->validated();
        if(isset($validated['phone'])){
            $donorPhone = $validated['phone'];
            unset($validated['phone']);
            // dd($donorPhone);
            $validated['donor_id'] = Donor::where('phone', '=', $donorPhone)->first()->id;
        }
        
        DB::transaction(function($query) use($donation, $validated){
            $old_proposal_id = $donation->proposal_id;
            $old_donor_id = $donation->donor_id;
            $old_document_nickname = $donation->document_nickname;

            $donation->update($validated);
    
            // update Document after updating the donation
            Document::updateOrCreateDocumentForDonation($donation, $old_proposal_id, $old_donor_id, $old_document_nickname);
        });
        

        return back()->with('res', ['message' => __('Donation Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        
        $donation->delete();
        Document::updateOrCreateDocumentForDonation($donation);
        return back()->with('res', ['message' => __('Donation Deleted Seccessfully'), 'type' => 'success']);
    }
}
