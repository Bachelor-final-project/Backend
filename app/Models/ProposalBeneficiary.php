<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class ProposalBeneficiary extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
    public static $controllable = true;
}
