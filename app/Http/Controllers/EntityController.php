<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonatingFormRequest;
use App\Http\Requests\StoreDonorRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Proposal;

use App\Models\Currency;

use App\Models\Donor;

use App\Models\Country;
use App\Models\Donation;
use App\Models\StripeM;
use App\Scopes\ForUserScope;

class EntityController extends Controller
{
    public static function routeName(){
        return Str::snake("Entity");
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
        
        return Inertia::render(Str::studly("Entity").'/Index', [
            "headers" => Entity::headers(),
            "items" => Entity::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }
    public function donatingForm(Request $request, string $donating_form_path)
    {
        // dd('hi');
        $entity = Entity::withoutGlobalScope(ForUserScope::class)->where('donating_form_path', $donating_form_path)->firstOrFail();
        $proposals = Proposal::withoutGlobalScope(ForUserScope::class)->where('entity_id', $entity->id)->where('status', Proposal::STATUSES['donatable'])->get();
        $showPayOnlineButton = false;
        foreach($proposals as $p) {
            if($p->isPayableOnline) {
                $showPayOnlineButton = true;
                break;
            }
        }
        return Inertia::render(Str::studly("Entity").'/DonatingForm', [
            "headers" => Proposal::guestHeaders(),
            "entity" => $entity,
            "proposals" => Proposal::withoutGlobalScope(ForUserScope::class)->where('entity_id', $entity->id)->public()->get(),
            'countries' => Country::select('id', 'name')->get(),
            'genders' => Donor::genders(),
            'payment_methods' => $entity->payment_methods,
            'show_payonline_button' => $showPayOnlineButton,
        ]);
    }
    public function storeDonatingForm(StoreDonatingFormRequest $request)
    {
        $data = $request->validated();
        $entity_donating_form_path = Str::afterLast(parse_url(back()->getTargetUrl(), PHP_URL_PATH), '/');
        $entity = Entity::where('donating_form_path', $entity_donating_form_path)->first();
        $data['tenant_id'] = $entity->tenant_id;
        $donor = Donor::firstOrCreate(['phone' => deterministicEncrypt($data['phone'])], $data);
        
        $onlinePayableDonations = [];
        $donationIds = [];
        // Handle donations if they exist
        if (!empty($data['donations'])) {
            foreach ($data['donations'] as $donation) {
                $d = Donation::create([
                    'donor_id' => $donor->id,
                    'proposal_id' => $donation['proposal_id'],
                    'document_nickname' => $data['document_nickname'],
                    'payment_method_id' => $data['payment_method_id'],
                    'amount' => $donation['amount'],
                    'currency_id' => $donation['currency_id'],
                    'tenant_id' => $entity->tenant_id,
                ]);
                if($donation['pay_online']) {
                    $onlinePayableDonations[] = $donation;
                    array_push($donationIds, $d->id);
                }
            }
        }
        if(empty($onlinePayableDonations))
            // return to_route($this->routeName() . '.index')->with('res', ['message' => __('Donor Saved Seccessfully'), 'type' => 'success']);
            return Inertia::render(Str::studly("Entity").'/CompletedDonatingForm', [
                "donor" => $donor,
                "donations" => $data['donations'],
            ]);

        $backUrl = back()->getTargetUrl();
        $sessionUrl = StripeM::doPayment($onlinePayableDonations, $donationIds, $backUrl);
        return Inertia::location($sessionUrl);

        
       }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Entity").'/Create', [
            'supervisors' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request)
    {
        $data = $request->validated();
        Entity::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Entity Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Entity $entity)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        return Inertia::render(Str::studly("Entity").'/Update', [
            //'options' => $regions,
            'entity' => $entity->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $validated = $request->validated();
        
        $entity->update($validated);
        return back()->with('res', ['message' => __('Entity Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();
        return back()->with('res', ['message' => __('Entity Deleted Seccessfully'), 'type' => 'success']);
    }
}
