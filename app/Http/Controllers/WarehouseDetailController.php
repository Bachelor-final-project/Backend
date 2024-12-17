<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseDetailRequest;
use App\Http\Requests\UpdateWarehouseDetailRequest;
use App\Http\Resources\WarehouseDetailResource;
use App\Models\WarehouseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WarehouseDetailController extends Controller
{

    public static function routeName()
    {
        return Str::snake("WarehouseDetail");
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        return view("", [
            'headers' => WarehouseDetail::headers(),
        ]);
    }

    public function indexApi(Request $request)
    {
        return WarehouseDetailResource::collection(WarehouseDetail::search($request)->sort($request)->paginate((request('per_page') ?? request('itemsPerPage')) ?? 15));
    }

    public function create()
    {
        return view("", [
            'data_to_send' => 'Hello, World!'
        ]);
    }

    public function store(StoreWarehouseDetailRequest $request)
    {
        $warehouseDetail = WarehouseDetail::create($request->validated());

        return new WarehouseDetailResource($warehouseDetail);
    }

    public function show(Request $request, WarehouseDetail $warehouseDetail)
    {
        return new WarehouseDetailResource($warehouseDetail);
    }

    public function edit()
    {
        return view("", [
            'data_to_send' => 'Hello, World!',
        ]);
    }

    public function update(UpdateWarehouseDetailRequest $request, WarehouseDetail $warehouseDetail)
    {
        $warehouseDetail->update($request->validated());

        return new WarehouseDetailResource($warehouseDetail);
    }

    public function destroy(Request $request, WarehouseDetail $warehouseDetail)
    {
        $warehouseDetail->delete();

        return new WarehouseDetailResource($warehouseDetail);
    }
}
