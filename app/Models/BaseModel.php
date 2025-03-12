<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    public static $controllable = true;

    protected $guarded = [];

    protected $hidden = [
        'updated_at'
    ];


    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    // public function getCreatedAtAttribute($old){
    //     return $this->created_at->setTimezone(auth()->user()->country? auth()->user()->country->time_zone: 'Etc/UTC');
    // }
    // public function getUpdatedAtAttribute($old){
    //     return $this->updated_at->setTimezone(auth()->user()->country? auth()->user()->country->time_zone: 'Etc/UTC');
    // }

    public function getCreatedAtDateTimeAttribute(){
        
        return date('Y-m-d - H:i', strtotime($this->created_at->setTimezone(auth()->user()->country? auth()->user()->country->time_zone: 'Etc/UTC')));
        // return date('Y-m-d - H:i', strtotime($this->created_at->setTimezone('Asia/Gaza')));
        // return Carbon::createFromFormat('Y-m-d H:i', $this->created_at)->timezone('Asia/Gaza');
    }
    public function scopeSearch($query, $request)
    {
         // Filter the parameters ending with "_filter" and transform their keys
         $filterColumns = collect($request->all())
         ->filter(function ($value, $key) {
             return str_ends_with($key, '_filter');
         })
         ->mapWithKeys(function ($value, $key) {
             // Remove "_filter" from the key
             $newKey = Str::replaceLast('_filter', '', $key);
             return [$newKey => $value];
         })
         ->toArray();

        //  dd($filterColumns);
         foreach ($filterColumns as $column => $value) {
            // dd(class_basename($this) );

             if(
                (empty($value) || $value == 0) && class_basename($this) != "Donation" ||
                (empty($value) || $value == 0) && class_basename($this) == "Donation" && $column != 'status'  ||
                ($value == '-1' && class_basename($this) == "Donation" && $column == 'status' )
                
                ){
                    
                 continue;
                }

                // dd($value);
             if(is_numeric($value)) $query->where($column, $value);
             
             else if(is_string($value)) $query->where($column,'like', "%$value%");
         }
    }
    public function scopeSort($query, $request)
    {
        $defaultSortColumn = 'id';
        $defaultSortDirection = 'desc';
        // Get sorting parameters
        $sortBy = $request->input('sortBy', $defaultSortColumn);
        // dd($sortBy);
         $sortDirection = $request->input('sortDesc', $defaultSortDirection);
        //  dd($request->input('sortDesc', [$defaultSortDesc])[0] === 'true'? 'desc':'asc');
        //  $sortDirection = strtolower($request->input('sort_direction', $defaultSortDesc));
 
         // Ensure sorting direction is either 'asc' or 'desc'
         if (!in_array($sortDirection, ['asc', 'desc'])) {
             $sortDirection = $defaultSortDirection;
         }

         $sortableComputedAttributes = [
            'calc_proposal_paid_amount' => function ($query) {
                return "(SELECT SUM(amount)
                     FROM donations 
                     WHERE donations.proposal_id = proposals.id 
                     AND donations.status = 2)";
            },
            'calc_proposal_remaining_amount' => function ($query) {
                return "( proposals.cost -
                     (SELECT  COALESCE(SUM(amount), 0)
                      FROM donations 
                      WHERE donations.proposal_id = proposals.id 
                      AND donations.status = 2))";
            },
            'calc_complete_donating_status_date' => function () {
                return "(SELECT logs.created_at
                         FROM logs 
                         WHERE logs.loggable_id = proposals.id 
                         AND logs.log_type = 1 
                         ORDER BY logs.created_at ASC 
                         LIMIT 1)";
            }
        ];
    
        if (isset($sortableComputedAttributes[$sortBy])) {
            // Apply sorting using raw expression
            return $query->orderByRaw($sortableComputedAttributes[$sortBy]($query) . " $sortDirection");
        }
         if (str_contains($sortBy, '.')) {
             // Handle sorting by related table
             [$relation, $relatedColumn] = explode('.', $sortBy, 2);
 
             if (method_exists($this, $relation)) { // check if there is really a relation between the two tables
                 $relatedModel = $this->{$relation}()->getRelated();
                 $relatedTable = $relatedModel->getTable();
                // dd(Schema::hasColumn($relatedTable, $relatedColumn));
                 // Ensure the related table has the requested column
                 if (Schema::hasColumn($relatedTable, $relatedColumn)) {
                     return $query
                         ->leftJoin($relatedTable, "{$relatedTable}.id", '=', "{$this->getTable()}.{$this->{$relation}()->getForeignKeyName()}")
                         ->orderBy("{$relatedTable}.{$relatedColumn}", $sortDirection)
                         ->select("{$this->getTable()}.*"); // Avoid duplicate columns
                 }
             }
         } elseif (Schema::hasColumn($this->getTable(), $sortBy)) {
             // Sort by the main model attribute
             return $query->orderBy($sortBy, $sortDirection);
         }
         
        //  dd($defaultSortDirection);
         // Fallback to default sorting
         return $query->orderBy($defaultSortColumn, $defaultSortDirection);
    }

    public function files()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }


}
