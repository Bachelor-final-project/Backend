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

class ProposalsExport implements FromCollection, WithEvents, WithHeadings, WithCustomStartCell, WithStrictNullComparison, ShouldAutoSize, WithStyles
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
        $headers = Proposal::headers();
        $dbHeadersToShow = array();
        foreach ($headers as $header) {
            $dbHeadersToShow[$header['key']] = $header['value'];
        }
        $usersCollection = Proposal::search($this->request)->sort($this->request)->get()->map->only(array_column($headers, 'key'));
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
        $event->sheet->getDelegate()->mergeCells('A1:E1');
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
        $highestRow = $sheet->getHighestRow();
        // $sheet->getStyle('A1')->getFont()->setBold(true);
        return [
            // Set horizontal alignment to center for all cells
            "1:$highestRow" => [
                'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
            ],
            '1:2' => [
                'font' => [
                        'bold' => true,
                    ]
            ]
        ];
    }
}