<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;


class EntityController extends Controller
{
    public static function routeName(){
        return Str::snake("Entity");
    }
     public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        return Inertia::render(Str::studly("Entity").'/Index', [
            "headers" => Entity::headers(),
            "items" => Entity::search($request)->sort($request)->paginate($this->pagination),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render(Str::studly("Entity").'/Create', [
            'supervisors' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request)
    {
        $data = $request->validated();
        Entity::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Entity Saved Seccessfully'), 'type' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Entity $entity)
    // {
        //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        return Inertia::render(Str::studly("Entity").'/Update', [
            //'options' => $regions,
            'entity' => $entity->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $validated = $request->validated();
        
        $entity->update($validated);
        return back()->with('res', ['message' => __('Entity Updated Seccessfully'), 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        $entity->delete();
        return back()->with('res', ['message' => __('Entity Deleted Seccessfully'), 'type' => 'success']);
    }
}
