<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStakeholder;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseStakeholderRequest;
use App\Http\Requests\UpdateWarehouseStakeholderRequest;
use App\Enums\StakeholderType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WarehouseStakeholderController extends Controller
{
    public static function routeName()
    {
        return Str::snake("WarehouseStakeholder");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return Inertia::render(Str::studly("WarehouseStakeholder").'/Index', [
            "headers" => WarehouseStakeholder::headers(),
            "items" => WarehouseStakeholder::search($request)->sort($request)->paginate($request->per_page ?? $this->pagination),
        ]);
    }

    public function create()
    {
        return Inertia::render(Str::studly("WarehouseStakeholder").'/Create', [
            'stakeholder_types' => StakeholderType::options()
        ]);
    }

    public function store(StoreWarehouseStakeholderRequest $request)
    {
        $data = $request->validated();
        WarehouseStakeholder::create($data);
        
        return to_route($this->routeName() . '.index')->with('res', ['message' => __('Warehouse Stakeholder Saved Successfully'), 'type' => 'success']);
    }

    public function edit(WarehouseStakeholder $warehouseStakeholder)
    {
        return Inertia::render(Str::studly("WarehouseStakeholder").'/Edit', [
            'stakeholder_types' => StakeholderType::options(),
            'warehouse_stakeholder' => $warehouseStakeholder->toArray()
        ]);
    }

    public function update(UpdateWarehouseStakeholderRequest $request, WarehouseStakeholder $warehouseStakeholder)
    {
        $validated = $request->validated();
        
        $warehouseStakeholder->update($validated);
        return back()->with('res', ['message' => __('Warehouse Stakeholder Updated Successfully'), 'type' => 'success']);
    }

    public function destroy(WarehouseStakeholder $warehouseStakeholder)
    {
        $warehouseStakeholder->delete();
        return back()->with('res', ['message' => __('Warehouse Stakeholder Deleted Successfully'), 'type' => 'success']);
    }
}