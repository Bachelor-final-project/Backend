<?php

namespace App\Imports;

use App\Models\Beneficiary;
use App\Models\ProposalBeneficiary;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class BeneficiaryImport implements  OnEachRow, WithHeadingRow
{
    private $proposal_id;

    public function __construct($proposal_id)
    {
        $this->proposal_id = $proposal_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        if(!$row[1] || empty($row[1])) return;

        // Find the beneficiary by national ID or create a new one
        $beneficiary = Beneficiary::firstOrNew(
            ['national_id' => $row[1]]
        );

        if (!empty($row[2])) {
            $beneficiary->name = $row[2];
        }
        if (!empty($row[3])) {
            $beneficiary->phone = $row[3];
        }
        if (!empty($row[5])) {
            $beneficiary->dob = $row[5];
        }
        if (!empty($row[6])) {
            $beneficiary->email = $row[6];
        }
        if (!empty($row[7])) {
            $beneficiary->father_national_id = $row[7];
        }


        // Save the beneficiary (create or update)
        $beneficiary->save();

        // Ensure a ProposalBeneficiary record exists
        ProposalBeneficiary::firstOrCreate(
            [
                'proposal_id' => $this->proposal_id,
                'beneficiary_id' => $beneficiary->id,
                'status' => 1,
                'notes' => !empty($row[8])? $row[8]: null,
                'count' => !empty($row[4])? $row[4]: null,
            ]
        );
    }
    public function headingRow(): int
    {
        return 1;
    }
}
