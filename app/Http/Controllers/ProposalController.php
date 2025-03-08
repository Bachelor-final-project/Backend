<?php

namespace App\Http\Controllers;

use App\Events\ProposalDonatingStatusApprovedWithDonatedAmount;
use App\Models\Proposal;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use App\Imports\BeneficiaryImport;
use App\Models\Currency;
use App\Models\Attachment;
use App\Models\ProposalType;
use App\Models\Entity;
use App\Models\Area;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\Donor;
use App\Models\Country;
use App\Models\Document;
use App\Models\Donation;
use App\Models\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class ProposalController extends Controller
{
    public static function routeName(){
        return Str::snake("Proposal");
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
        
        return Inertia::render(Str::studly("Proposal").'/Index', [
            "headers" => Proposal::headers(),
            "statuses" => Proposal::statuses(),
            'entities' => Entity::get(),
            'currencies' => Currency::get(),
            'proposalTypes' => ProposalType::get(),
            'areas' => Area::get(),
            "items" => Proposal::search($request)->sort($request)->paginate($this->pagination),
            "users" => User::get()
        ]);
    }
    public function overview(Request $request)
    {
        
        return Inertia::render(Str::studly("Proposal").'/Overview', [
            "headers" => Proposal::overviewHeaders(),
            "statuses" => Proposal::statuses(),
            'entities' => Entity::get(),
            'currencies' => Currency::get(),
            'proposalTypes' => ProposalType::get(),
            'areas' => Area::get(),
            "items" => Proposal::where('status', 1)->search($request)->sort($request)->paginate($this->pagination),
            "users" => User::get()
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function guestIndex(Request $request)
    {
        
        return Inertia::render(Str::studly("Proposal").'/GuestIndex', [
            "headers" => Proposal::guestHeaders(),
            "proposals" => Proposal::get(),
            'countries' => Country::select('id', 'name')->get(),
            'genders' => Donor::genders(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Proposal").'/Create', [
            // 'status_options' => Proposal::statuses(),
            'currencies' => Currency::all(),
            'proposal_types' => ProposalType::all(),
            'entities' => Entity::all(),
            'areas' => Area::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProposalRequest $request)
    {
        $data = $request->validated();

        $file = $data['files']? $data['files'][0]: null;
        unset($data['files']);
        // if($data['files']){
        //     $file = $data['files'][0];
        // }
        $proposal = Proposal::create($data);

        if($file) {
            Attachment::storeAttachment([$file], $proposal->id, 'proposal', 1);
        }
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Proposal Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proposal $proposal)
    {
        return Inertia::render(Str::studly("Proposal").'/Show', [
            //'options' => $regions,
            'proposal' => $proposal->toArray(),
            'currencies' => Currency::all(),
            'proposal_types' => ProposalType::all(),
            'donations' => $proposal->donations()->paginate($this->pagination),
            'donations_headers' => Donation::headers(),
            'documents' => $proposal->documents()->paginate($this->pagination),
            'documents_headers' => Document::headers(),
            'beneficiaries' => $proposal->beneficiaries()->paginate($this->pagination),
            'beneficiaries_headers' => Beneficiary::headers(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        return Inertia::render(Str::studly("Proposal").'/Edit', [
            //'options' => $regions,
            'proposal' => $proposal->toArray(),
            'currencies' => Currency::all(),
            'proposal_types' => ProposalType::all(),
            'entities' => Entity::all(),
            'areas' => Area::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalRequest $request, Proposal $proposal)
    {
        
        // dd($request);
        // Log::debug($proposal);
        $validated = $request->validated();
        // update the propsal with the new values without storing it, so that we can check the new and the original values in the ProposalPolicy class
        // $proposal->fill($validated); 
        
        // Gate::authorize('update', [$proposal, $request['status']]);
        
        $logType = 4;
        
        // check if there is a donatingAmount and a new donation record is needed to be added 
        // dd($request->donatingAmount);
        if($request->status == 2 && $proposal->status != $request->status){
            $logType = 1;
            if(!empty($request->donatingAmount)){
                //create new donation;
                $recipient_id = isset($validated['recipient']) ? $validated['recipient'] : null;
                $receipts = isset($validated['receipts']) ? $validated['receipts'] : null;
                unset($validated['recipient']);
                unset($validated['receipts']);
                // dd($request->donatingAmount, $recipient_id, $receipts);
                ProposalDonatingStatusApprovedWithDonatedAmount::dispatch($proposal, $request->donatingAmount, $recipient_id, $receipts);
            }

        }
        if($request->arabicVideoFile){
            $file = $validated['arabicVideoFile'][0];
            unset($validated['arabicVideoFile']);
            Attachment::storeAttachment([$file], $proposal->id, 'proposal', 2);
            $logType = 2;
        }
        if($request->englishVideoFile){
            $file = $validated['englishVideoFile'][0];
            unset($validated['englishVideoFile']);
            Attachment::storeAttachment([$file], $proposal->id, 'proposal', 3);
            $logType = 2;
        }
        if($request->beneficiariesFile){
            $file = $validated['beneficiariesFile'][0];
            unset($validated['beneficiariesFile']);
            Attachment::storeAttachment([$file], $proposal->id, 'proposal', 4);
            Excel::import(new BeneficiaryImport($proposal->id), $file);
            $logType = 3;
        }
        
        $proposal->update($validated);
        Log::storeLog( $proposal->id, 'proposal', $logType);

        return back()->with('res', ['message' => __('Proposal Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return back()->with('res', ['message' => __('Proposal Deleted Seccessfully'), 'type' => 'success']);
    }
}
