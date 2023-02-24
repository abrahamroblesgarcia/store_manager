<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contexts\Store as StoreContext;
use App\Http\Requests\StoreCreationRequest;
use App\Http\Requests\StoreUpdateRequest;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $store = new StoreContext();
        $store_list = $store->get_all();
        if (empty($store_list)) {
            $response = ['message' =>'There are currently no stores'];
        } else {
            $response = $store_list;
        }

        return response()->json($response);
    }

    public function get(Request $request)
    {
        $store = new StoreContext();
        $response = $store->get($request->store_hash);

        return response()->json($response);
    }

    public function create(StoreCreationRequest $request)
    {
        $store_name = $request->name;
        $store_products = $request->products;

        $store = new StoreContext();
        $store_hash = false;
        if (is_null($store_products)) {
            $store_hash = $store->create($store_name);
        } else {
            $store_hash = $store->create($store_name, $store_products);
        }
        return response()->json([
            "message" => "Store '.$store_name.' is succesfully created.",
            "store_hash" => $store_hash
        ], 201);
    }

    public function update(StoreUpdateRequest $request)
    {
        $store_hash = $request->store_hash;
        $store_name = $request->name;
        $store_products = $request->products;

        $store = new StoreContext();
        if (is_null($store_products)) {
            $store->update($store_hash, $store_name);
        } else {
            $store->update($store_hash, $store_name, $store_products);
        }

        return response()->json(["message" => "Store '.$store_hash.' is succesfully updated."]);
    }

    public function delete(Request $request)
    {
        $store_hash = $request->store_hash;

        $store = new StoreContext();
        $store->delete($store_hash);

        return response()->json(["message" => "Store '.$store_hash.' is succesfully deleted."]);
    }
}
