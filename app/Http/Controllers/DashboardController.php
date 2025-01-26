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
        // $all_types = FormType::all();
        // $all_regions = Region::all();
        if (auth()->user()->type == 1) {
            // $total_grievrance = Form::search($request)->count();
            // $forms = Form::search($request)->sort($request)->paginate($this->pagination, ['*'], 'forms_page');
            // $users = User::where('type', 2)->search($request)->sort($request)->paginate($this->pagination, ['*'], 'users_page');
            
            return Inertia::render('Dashboard', [
                "froms_headers"          => [],
                "all_froms"              => [],
                "user_headers"           => [],
                "all_users"              => [],
                "total_grievrance"       => [],
                "all_types"              => [],
                "all_regions"            => [],
                "stacked_group_chart"    => [
                    [
                        "name" => "Q1 Budget",
                        "group" => "budget",
                        "data" => [44000, 55000, 41000, 67000, 22000]
                    ],
                    [
                        "name" => "Q1 Actual",
                        "group" => "actual",
                        "data" => [48000, 50000, 40000, 65000, 25000]
                    ],
                    [
                        "name" => "Q2 Budget",
                        "group" => "budget",
                        "data" => [13000, 36000, 20000, 8000, 13000]
                    ],
                    [
                        "name" => "Q2 Actual",
                        "group" => "actual",
                        "data" => [20000, 40000, 25000, 10000, 12000]
                    ]
                ],
                "group_chart"            => [],
                "pointed_chart"          => [],
                "global_statistics"      => [],
                "agents_count"           => 0,
                "gender_total_chart"     => [],
                "proposalsByStatus"     => Proposal::getProposalsByStatusChartData(),
                "proposalsByTypes"     => Proposal::getProposalsByTypesChartData(),
                "donationsByStatues"     => Donation::getDonationsByStatuesChartData(),
                "documentsByStatues"     => Document::getDocumentsByStatuesChartData(),
                "approvedDonationLast30Days"     => Donation::getApprovedDonationLast30DaysChartData(),
                "completedProposalsLast30Days"     => Proposal::getCompletedProposalsLast30DaysChartData(),
                "benefitsLast30Days"     => ProposalBeneficiary::getBenefitsLast30DaysChartData(),
                "donatingStatusProposalsStackedGroup"     => Proposal::getDonatingStatusProposalsStackedGroup(),
            ]);
            // return Inertia::render('Dashboard', [
                // "froms_headers" => Form::headers(),
                // "all_froms" => $forms,
                // "user_headers" => User::agents_headers(),
                // "all_users" => $users,
                // "total_grievrance" => $total_grievrance,
                // "all_types" => $all_types,
                // "all_regions" => $all_regions,
                // "stacked_group_chart" => Form::getTotalFormsForRegion($request),
                // "group_chart" => Form::getTypesRegionForms($request),
                // "pointed_chart" => Form::getPointedChartData($request),
                // "global_statistics" => Form::getStatistics($request),
                // "agents_count" => Form::getAgentsCounts(),
                // "gender_total_chart" => Form::getGenderTotal($request),
            // ]);
        } else {
            // $total_grievrance = Form::search($request)->where('region_id', auth()->user()->region_id)->count();
            // $forms = Form::search($request)->sort($request)->where('region_id', auth()->user()->region_id)->paginate($this->pagination);
            return Inertia::render('Dashboard', [
                // "froms_headers" => Form::headers(),
                // "all_froms" => $forms,
                // "all_types" => $all_types,
                // "all_regions" => $all_regions,
                // "column_chart" => Form::getTotalPendingForRegion(),
                // "basic_chart" => Form::getTotalTypes(),
                // "global_statistics" => Form::getStatistics($request),
                // "total_grievrance" => $total_grievrance,
            ]);
        }
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