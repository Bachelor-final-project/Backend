<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Proposal;
use Illuminate\Http\Request;
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
        
        return Inertia::render(Str::studly("Donation").'/Index', [
            "headers" => Donation::headers(),
            'currencies' => Currency::get(),
            'proposals' => Proposal::get(),
            'statuses' => Donation::statuses(),
            'donors' => Donor::get(),
            "items" => Donation::search($request)->sort($request)->paginate($request->per_page?? $this->pagination),
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
            'proposals' => Proposal::where('status', '=', Proposal::STATUSES['donatable'])->get(),
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
        return Inertia::render(Str::studly("Donation").'/Edit', [
            'status_options' => Donation::statuses(),
            'currencies' => Currency::all(),
            'proposals' => Proposal::where('status', '=', Proposal::STATUSES['donatable'])->get(),
            'donation' => $donation->toArray()
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
        $old_proposal_id = $donation->proposal_id;
        
        $donation->update($validated);

        // update Document after updating the donation
        Document::updateOrCreateDocumentForDonation($donation, $old_proposal_id);
        

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
