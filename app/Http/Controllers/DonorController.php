<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonorRequest;
use App\Http\Requests\UpdateDonorRequest;
use App\Models\Donor;
use App\Models\Country;
use App\Models\Donation;
use App\Models\StripeM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class DonorController extends Controller
{
    public static function routeName(){
        return Str::snake("Donor");
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
        return Inertia::render(Str::studly("Donor").'/Index', [
            "headers" => Donor::headers(),
            "items" => Donor::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Donor").'/Create', [
            'countries' => Country::select('id', 'name')->orderBy('name')->get(),
            'genders' => Donor::genders(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonorRequest $request)
    {
        $data = $request->validated();
        $donor = Donor::firstOrCreate(['phone' => $data['phone']], $data);

        $onlinePayableDonations = [];
        $donationIds = [];
        // Handle donations if they exist
        if (!empty($data['donations'])) {
            foreach ($data['donations'] as $donation) {
                $d = Donation::create([
                    'donor_id' => $donor->id,
                    'proposal_id' => $donation['proposal_id'],
                    'amount' => $donation['amount'],
                    'currency_id' => $donation['currency_id'],
                ]);
                if($donation['pay_online']) {
                    $onlinePayableDonations[] = $donation;
                    array_push($donationIds, $d->id);
                }
            }
        }
        if(empty($onlinePayableDonations))
            return to_route($this->routeName() . '.index')->with('res', ['message' => __('Donor Saved Seccessfully'), 'type' => 'success']);

        $backUrl = back()->getTargetUrl();
        // dd($backUrl);
        $sessionUrl = StripeM::doPayment($onlinePayableDonations, $donationIds, $backUrl);
        // dd($sessionUrl);
        return Inertia::location($sessionUrl);
        // return redirect()->away($sessionUrl);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Donor $donor)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        return Inertia::render(Str::studly("Donor").'/Edit', [
            'countries' => Country::select('id', 'name')->get(),
            'genders' => Donor::genders(),
            'donor' => $donor->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDonorRequest $request, Donor $donor)
    {
        $validated = $request->validated();
        
        $donor->update($validated);
        return back()->with('res', ['message' => __('Donor Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
        $donor->delete();
        return back()->with('res', ['message' => __('Donor Deleted Seccessfully'), 'type' => 'success']);
    }
}
