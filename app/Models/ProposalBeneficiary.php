<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalBeneficiary extends BaseModel
{
    use HasFactory;
    public static $controllable = true;

    
    public static function getBenefitsLast30DaysChartData(){
        $proposals = ProposalBeneficiary::selectRaw('status, COUNT(*) as count, date(created_at) as date')
        ->where('created_at', '>', now()->subDays(30)->endOfDay())
        ->groupByRaw('status, date(created_at)')
        ->get();

            $result = [
                "data" => $proposals->pluck('count')->toArray()
            ];

        return $result;
    }
}
