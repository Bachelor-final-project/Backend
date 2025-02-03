<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use App\Exports\UserExport;
use App\Exports\WarehousesExport;
use App\Exports\UnitsExport;
use App\Exports\ProposalsExport;
use App\Exports\ItemsExport;
use App\Exports\CurrenciesExport;
use App\Exports\BeneficiariesExport;
use App\Exports\ProposalsFromViewExport;
use App\Exports\DonorsExport;
use App\Exports\DonationsExport;
use App\Models\Proposal;
use Maatwebsite\Excel\Facades\Excel;

class GeneralController extends Controller
{
    public function changeLanguage($locale)
    {
        // dd($locale);
        try {
            // dd("fgfg");
            if (array_key_exists($locale,  config('locale.languages'))) {
                Cookie::queue(Cookie::make('locale', $locale, 52560000, null, null, false, false));
                Cookie::queue('test', 'Hello,World!');
                App::setLocale($locale);
                return redirect()->back();
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function importUsers(Request $request)
    {
        $usersExport = new UserExport($request);
        return Excel::download($usersExport, 'Users.xlsx');
    }
    public function importWarehouses(Request $request)
    {
        $warehousesExport = new WarehousesExport($request);
        return Excel::download($warehousesExport, 'WarehousesExport.xlsx');
    }
    public function importUnits(Request $request)
    {
        $unitExport = new UnitsExport($request);
        return Excel::download($unitExport, 'UnitsExport.xlsx');
    }
    public function importProposals(Request $request)
    {
        $proposalsExport = new ProposalsExport($request);
        return Excel::download($proposalsExport, 'ProposalsExport.xlsx');
    }
    public function importItems(Request $request)
    {
        $itemExport = new ItemsExport($request);
        return Excel::download($itemExport, 'ItemsExport.xlsx');
    }
    public function importCurrencies(Request $request)
    {
        $currencyExport = new CurrenciesExport($request);
        return Excel::download($currencyExport, 'CurrenciesExport.xlsx');
    }
    public function importBeneficiaries(Request $request)
    {
        $beneficiariesExport = new BeneficiariesExport($request);
        return Excel::download($beneficiariesExport, 'BeneficiariesExport.xlsx');
    }
    public function proposalPDF(Request $request){
        return Excel::download(new ProposalsFromViewExport($request), 'invoices.xlsx');
       
    }
    public function importDonors(Request $request){
        return Excel::download(new DonorsExport($request), 'DonorsExport.xlsx');
       
    }
    public function importDonations(Request $request){
        return Excel::download(new DonationsExport($request), 'DonationsExport.xlsx');
       
    }
    
}
