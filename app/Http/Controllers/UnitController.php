<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UnitController extends Controller
{

    public static function routeName()
    {
        return Str::snake("Unit");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return view("dashboard." . $this->routeName() . ".index", [
            'headers' => $this->getModelInstance()::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return UnitResource::collection(Unit::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("dashboard." . $this->routeName() . ".create", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $this->getModelInstance()
        ]);
    }

    public function store(StoreUnitRequest $request)
    {
        $unit = Unit::create($request->validated());

        return new UnitResource($unit);
    }

    public function show(Request $request, Unit $unit)
    {
        return view("dashboard." . $this->routeName() . ".show", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $unit
        ]);
    }

    public function edit(Unit $unit)
    {
        return view("dashboard." . $this->routeName() . ".edit", [
            'data_to_send' => 'Hello, World!',
            $this->routeName() => $unit
        ]);
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        return new UnitResource($unit);
    }

    public function destroy(Request $request, Unit $unit)
    {
        $unit->delete();

        return new UnitResource($unit);
    }
}
