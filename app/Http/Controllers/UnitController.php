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
        return view("", [
            'headers' => Unit::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return UnitResource::collection(Unit::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("", [
            'data_to_send' => 'Hello, World!'
        ]);
    }

    public function store(StoreUnitRequest $request)
    {
        $unit = Unit::create($request->validated());

        return new UnitResource($unit);
    }

    public function show(Request $request, Unit $unit)
    {
        return new UnitResource($unit);
    }

    public function edit()
    {
        return view("", [
            'data_to_send' => 'Hello, World!',
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
