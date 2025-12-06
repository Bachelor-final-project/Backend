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
    protected $appends = [ 'supervisor_name', 'donating_form_link', 'home_page_link', 'country_name', 'completed_projects_count', 'number_of_public_proposal', 'donations_count'];
    protected $with = [ 'supervisor', 'country'];
    protected $casts = [
        'home_title' => 'array',
        'home_description' => 'array',
    ];
    public static $controllable = true;

    public function getSupervisorNameAttribute(){
        return $this->supervisor?->name;
    }
    public function getDonatingFormLinkAttribute(){
        return url("/donating-form/{$this->donating_form_path}");
    }
    public function getHomePageLinkAttribute(){
        return url("/home/{$this->donating_form_path}");
    }
    public function getCountryNameAttribute()
    {
        return $this->country?->name ?? null;
    }    public function supervisor(){
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function payment_methods(){
        return $this->hasMany(PaymentMethod::class, 'entity_id');
    }
    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function proposals(){
        return $this->hasMany(Proposal::class, 'entity_id');
    }
    public function getCompletedProjectsCountAttribute(){
        $completedProposals = $this->proposals()->whereNotIn('status', [1])->count();
        return $this->initial_completed_projects + $completedProposals;
    }
    public function getNumberOfPublicProposalAttribute(){
        return $this->proposals()->public()->count();
    }
    public function getDonationsCountAttribute(){
        return $this->proposals()->withCount('donations')->get()->sum('donations_count');
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
            ['sortable' => true, 'value' => 'home page path', 'key' => 'home_page_link', 'type' => 'link'],
            ['sortable' => true, 'value' => 'supervisor name', 'key' => 'supervisor_name'],
            ['sortable' => true, 'value' => 'country', 'key' => 'country_name'],
            ['sortable' => true, 'value' => 'whatsapp number', 'key' => 'whatsapp_number'],
            ['sortable' => true, 'value' => 'completed projects', 'key' => 'completed_projects_count'],
            // ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
