<?php

namespace App\Models;

use App\Traits\ForUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

use App\Models\PaymentMethod;

class Entity extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped, ForUserTrait;
    protected $appends = [ 'supervisor_name', 'donating_form_link'];
    protected $with = [ 'supervisor'];
    public static $controllable = true;

    public function getSupervisorNameAttribute(){
        return $this->supervisor->name;
    }
    public function getDonatingFormLinkAttribute(){
        return url("/donating-form/{$this->donating_form_path}");
    }
    public function supervisor(){
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function payment_methods(){
        return $this->hasMany(PaymentMethod::class, 'entity_id');
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
                $query->where('supervisor_id', $user->id);
                break;
            default:
                $query->whereRaw('1=0');
                break;
        }
    }
    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'donating form path', 'key' => 'donating_form_link', 'type' => 'link'],
            ['sortable' => true, 'value' => 'supervisor name', 'key' => 'supervisor_name'],
            // ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
