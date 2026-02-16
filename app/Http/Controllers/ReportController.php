<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReportController extends Controller
{
    public static function routeName()
    {
        return Str::snake("Report");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        $fromDate = $request->from_date ? \Carbon\Carbon::parse($request->from_date)->startOfDay() : null;
        $toDate = $request->to_date ? \Carbon\Carbon::parse($request->to_date)->endOfDay() : null;

        return Inertia::render('Report/Index', [
            "approvedDonationLast30Days" => Donation::getApprovedDonationLast30DaysChartData($fromDate, $toDate),
            "totalApprovedDonationLast30Days" => Donation::getTotalApprovedDonationLast30DaysChartData($fromDate, $toDate),
            "completedProposalsLast30Days" => Proposal::getCompletedProposalsLast30DaysChartData($fromDate, $toDate),
            "filters" => [
                "from_date" => $request->from_date,
                "to_date" => $request->to_date,
            ]
        ]);
    }
}
