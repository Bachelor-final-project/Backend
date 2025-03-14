<?php

namespace App\Imports;

use App\Models\Beneficiary;
use App\Models\ProposalBeneficiary;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use Carbon\Carbon;

class BeneficiaryImport implements WithStartRow, ToModel
{
    private $proposal_id;

    public function __construct($proposal_id)
    {
        $this->proposal_id = $proposal_id;
    }
    
    public function model(array $row)
    {
        $dob = $row[5];
        $dob = str_replace('\\','-',$dob);
        $dob = Carbon::parse($dob)->toDateString();
        $socialStatus = Beneficiary::$arabicSocialStatusMapping($row[8] ?? '0');
        $beneficiary = Beneficiary::updateOrCreate(
        [
            'national_id' => $row[1]
        ],
        [
            'name' => $row[2],
            'phone' => $row[3],
            'dob' => $dob,
            'email' => $row[6],
            'num_of_family_members' => $row[7],
            'social_status' => $socialStatus,
            'father_national_id' => $row[9] ?? '0',
            'address' => $row[11],
        ]);

        ProposalBeneficiary::updateOrCreate(
            [
                'proposal_id' => $this->proposal_id,
                'beneficiary_id' => $beneficiary->id,
            ],
            [
                'status' => 1,
                'notes' => !empty($row[8])? $row[10]: null,
                'count' => !empty($row[4])? $row[4]: null,
            ]
        );

    }
    public function startRow(): int
    {
        return 2;
    }
}
