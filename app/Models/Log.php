<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends BaseModel
{
    use HasFactory;
    protected $appends = ['formatted_created_at'];
    public static $controllable = true;

    public function loggable()
    {
        return $this->morphTo();
    }
    public function getFormattedCreatedAtAttribute(){
        // return 'dd';

        return date('Y-m-d', strtotime($this->created_at));
    }
    public static function storeLog($loggableId, $loggableType, $logType = 1, $notes = null) {
        // Create the log record
        Log::create([
            'loggable_type' => $loggableType,
            'loggable_id' => $loggableId,
            'log_type' => $logType, // Default to 1 if not provided
            'notes' => $notes, // Default to null if not provided
            'user_id' => auth()->id() ?? null, // Assuming user_id should be the current authenticated user
        ]);
    }

    public function getLogTypeNameAttribute(){
        $types = [];
        switch ($this->loggable_type) {
            case 'proposal':
                $types = [
                    1 => __("Complete Donating Status"),
                    2 => __("Complete Execution Status"),
                    3 => __("Complete Archiving Status"),
                    4 => __("General Update"),
                ];
                break;
            
            default:
                return $this->loggable_type;
        }

        return $types[$this->log_type];
    }
}
