<?php

namespace App\Models;

use App\Traits\ForUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class ProposalBeneficiary extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped, ForUserTrait;
    public static $controllable = true;

    public function proposal(){
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    //scopes
    public function scopeForUser($query, $user)
    {
        switch ($user->type) {
            case 1: // proposal_director
            case 3: // donations_director
            case 4: // warehouses_director
            case 5: // media_director
                // access to all records
                break;
                
            case 2: // entity_director
                $query->has('proposal');
                break;
            default:
                $query->whereRaw('1=0');
                break;
        }
    }
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
