<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entity extends BaseModel
{
    use HasFactory;
    protected $appends = [ 'supervisor_name'];
    protected $with = [ 'supervisor'];
    public static $controllable = true;

    public function getSupervisorNameAttribute(){
        return $this->supervisor->name;
    }
    // public function getFormLinkAttribute(){
    // }
    public function supervisor(){
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public static function headers($user = null)
    {
        return [
            ['sortable' => true, 'value' => 'name', 'key' => 'name'],
            ['sortable' => true, 'value' => 'donating form path', 'key' => 'donating_form_path'],
            ['sortable' => true, 'value' => 'supervisor name', 'key' => 'supervisor_name'],
            // ['sortable' => true, 'value' => 'description', 'key' => 'description'],
            ['sortable' => true, 'value' => 'actions', 'key' => 'actions', 'actions' => ['show', 'update', 'delete']],
        ];
    }
}
