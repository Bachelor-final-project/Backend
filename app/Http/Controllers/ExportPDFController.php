<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Document;
use App\Models\Donation;
use App\Models\Proposal;
use App\Services\PdfService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;
use Spatie\LaravelPdf\Facades\Pdf;

class ExportPDFController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function proposal(Request $request){
        $id = $request->id;
        $item = Proposal::where('id', $id)->first();
        $viewData = [
            'item' => $item,
            'documents_headers' => Document::headersForProposal(),
            'donations_headers' => Donation::headersForProposal(),
            'beneficiaries_headers' => Beneficiary::headers(),
        ];
        // return View::make('ExportPDF/proposal', $viewData)->render();
        
        $pdfPath = $this->pdfService->generatePdf($viewData);

        return response()->download($pdfPath);
    }
}
