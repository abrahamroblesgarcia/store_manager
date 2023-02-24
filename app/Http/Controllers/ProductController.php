<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contexts\Product as ProductContext;

class ProductController extends Controller
{
    public function sell(Request $request)
    {
        $store_hash = $request->store_hash;
        $product_hash = $request->product_hash;
        $units_to_sell = $request->units_to_sell;

        try {
            $product = new ProductContext();
            $response = $product->sell($product_hash, $units_to_sell);
            return response()->json(["message" => $response]);
        } catch(\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 422);
        }
    }
}
