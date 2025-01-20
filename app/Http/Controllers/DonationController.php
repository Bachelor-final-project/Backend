<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Models\Donation;
use App\Models\Currency;
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
            "items" => Donation::search($request)->sort($request)->paginate($this->pagination),
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
        $data['donor_id'] = Donor::where('phone', '=', $donorPhone)->first()->id;
        Donation::create($data);
        
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
        
        $donation->update($validated);
        return back()->with('res', ['message' => __('Donation Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();
        return back()->with('res', ['message' => __('Donation Deleted Seccessfully'), 'type' => 'success']);
    }
}
