<?php

namespace App\Http\Controllers;

// use App\Exports\GrievancesExport;
// use App\Exports\OfficersExport;
// use App\Exports\StatisticsExport;
// use App\Mail\ClosedForm;
// use App\Models\AgentWithStatus;
// use App\Models\Form;
// use App\Models\FormType;
// use App\Models\Region;
// use App\Models\User;
// use App\Models\UserWithStatus;

use App\Models\Document;
use App\Models\Donation;
use App\Models\Proposal;
use App\Models\ProposalBeneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
// use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

            return Inertia::render('Dashboard', [
                "proposalsByStatus"     => Proposal::getProposalsByStatusChartData(),
                "proposalsByTypes"     => Proposal::getProposalsByTypesChartData(),
                "donationsByStatues"     => Donation::getDonationsByStatuesChartData(),
                "documentsByStatues"     => Document::getDocumentsByStatuesChartData(),
                "approvedDonationLast30Days"     => Donation::getApprovedDonationLast30DaysChartData(),
                "completedProposalsLast30Days"     => Proposal::getCompletedProposalsLast30DaysChartData(),
                "benefitsLast30Days"     => ProposalBeneficiary::getBenefitsLast30DaysChartData(),
                "donatingStatusProposalsStackedGroup"     => Proposal::getDonatingStatusProposalsStackedGroup(),
                "proposals_overview"     => Proposal::where('status', 1)->search($request)->sort($request)->paginate($request->per_page?? $this->pagination),
                "proposals_overview_headers"     => Proposal::overviewHeaders(),

            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        //
    }

    public function importGrievances(Request $request)
    {
        // $grievancesExport = new GrievancesExport($request);
        // return Excel::download($grievancesExport, 'Reports.xlsx');
    }
    public function importOfficers(Request $request)
    {
        // $officersExport = new OfficersExport($request);
        // return Excel::download($officersExport, 'ESP_Officers.xlsx');
    }
    public function importStatistics(Request $request)
    {
        // $staticsExport = new StatisticsExport($request);
        // // dd($staticsExport);
        // return Excel::download($staticsExport, 'Statistics.xlsx');
    }
}