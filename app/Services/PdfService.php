<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PdfService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function generatePdf(array $viewData): string
    {
        // dd(env('gotenberg_url'));
        // Step 1: Ensure the model is passed as 'item' in view data
        $model = isset($viewData['item']) ? $viewData['item'] : null;
        
        if (!$model) {
            throw new \InvalidArgumentException("Model (item) is required for PDF generation.");
        }
        $className = class_basename($model);
        // Step 1: Render the Blade View as HTML based on the model name
        $viewName = 'ExportPDF.' . strtolower($className); // Dynamically generate the Blade view name
        $html = View::make($viewName, $viewData)->render();

        // Step 2: Send the HTML to Gotenberg
        $response = $this->client->post(env('gotenberg_url') . '/forms/chromium/convert/html', [
            'multipart' => [
                [
                    'name'     => 'files',
                    'contents' => $html,
                    'filename' => 'index.html',
                ],
                
                
            ],
            
            
        ]);

        // Step 3: Ensure directory exists
        $directory = storage_path("app/public/exported_pdf/{$className}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        // Step 4: Generate a unique file name
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $uniqueId = uniqid();
        $fileName = "{$className}_pdf_report_{$timestamp}_{$uniqueId}.pdf";
        $pdfPath = "{$directory}/{$fileName}";

        // Step 5: Save the PDF file
        file_put_contents($pdfPath, $response->getBody());

        return $pdfPath; // Return the file path
    }
}
