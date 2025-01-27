<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TenantAttributeTrait;
use App\Traits\TenantScoped;

class Entity extends BaseModel
{
    use HasFactory, TenantAttributeTrait, TenantScoped;
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
