<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{

    public static function routeName()
    {
        return Str::snake("Warehouse");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return view("", [
            'headers' => Warehouse::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return WarehouseResource::collection(Warehouse::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("", [
            'data_to_send' => 'Hello, World!'
        ]);
    }

    public function store(StoreWarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated());

        return new WarehouseResource($warehouse);
    }

    public function show(Request $request, Warehouse $warehouse)
    {
        return new WarehouseResource($warehouse);
    }

    public function edit()
    {
        return view("", [
            'data_to_send' => 'Hello, World!',
        ]);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());

        return new WarehouseResource($warehouse);
    }

    public function destroy(Request $request, Warehouse $warehouse)
    {
        $warehouse->delete();

        return new WarehouseResource($warehouse);
    }
}
