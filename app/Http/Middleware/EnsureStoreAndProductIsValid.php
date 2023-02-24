<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Contexts\Product as ProductContext;

class EnsureStoreAndProductIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make(
            $request->route()->parameters(),
            [
                'store_hash' => ['exists:stores,store_hash'],
                'product_hash' => ['exists:products,product_hash',
                    function (string $attribute, mixed $value, Closure $fail) use ($request) {
                        $product = new ProductContext();
                        if (!$product->is_product_of_store($value, $request->store_hash)) {
                            $fail("{$attribute} {$value} is invalid or dosen't belongs to selected store");
                        }
                    }]
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $next($request);
    }
}
