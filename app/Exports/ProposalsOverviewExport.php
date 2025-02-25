<?php

namespace App\Exports;

use App\Models\Proposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProposalsOverviewExport implements FromCollection, WithEvents, WithHeadings, WithCustomStartCell, WithStrictNullComparison, ShouldAutoSize, WithStyles
{
    use RegistersEventListeners;
    public $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $headers = Proposal::overviewHeaders();
        $dbHeadersToShow = array();
        foreach ($headers as $header) {
            $dbHeadersToShow[$header['key']] = $header['value'];
        }
        $usersCollection = Proposal::where('status', 1)->search($this->request)->sort($this->request)->get()->map->only(array_column($headers, 'key'));
        // logger("Users");
        // logger($usersCollection);
        $usersCollection = $usersCollection->map(function ($user) use ($dbHeadersToShow) {
            foreach ($user as $key => $value) {
                if (__($dbHeadersToShow[$key]) == $key)
                    continue;
                $user[__($dbHeadersToShow[$key])] = $value;
                unset($user[$key]);
            }
            return $user;
        });
        // logger($usersCollection);
        return $usersCollection;
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        $ar_datetime = Carbon::now()->format('H:i:s d-m-Y');
        $en_datetime = Carbon::now()->format('Y-m-d H:i:s');
        $appLocale = app()->getLocale();
        $sheet = $event->sheet;
        $highestColumn = Coordinate::stringFromColumnIndex(count(Proposal::overviewHeaders()));
        $event->sheet->getDelegate()->mergeCells('A1:' . $highestColumn .'1');
        $event->sheet->getDelegate()->setRightToLeft($appLocale == 'ar');
        $sheet->setCellValue('A1', __("Exported At") . ": " . ${$appLocale . "_datetime"});
    }

    public function headings(): array
    {
        // dd($this->collection()->first());
        if (!$this->collection()->first())
            return [];
        return array_keys($this->collection()->first());
    }

    public function startCell(): string
    {
        return 'A2';
    }
    public function styles(Worksheet $sheet)
    {
        $headerStyle = [
            'font' => 
               
                [
                    'name' => 'Calibri',
                    'size' => 14,
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            'fill' => 
               
                [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '548135'],
                    'endColor' => ['rgb' => 'FFFFFF'],
                ],
            'borders' => [
                    'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                ],
            'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
        ];

        $default_style = [
            'font' => [
                'name' => 'Arial',
                'color' => ['rgb' => '000000'],
                'size' => 12,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'AAAAAA'],
                ],
            ],
        ];
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        // $sheet->getStyle('A1')->getFont()->setBold(true);
        return [
            // Set horizontal alignment to center for all cells
            "a3:$highestColumn$highestRow" => $default_style,
            "a1:$highestColumn"."2" => $headerStyle
        ];
    }
}