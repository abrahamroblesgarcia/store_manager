<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class EnsureStoreIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make($request->route()->parameters(), ['store_hash' => 'exists:stores,store_hash']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $next($request);
    }
}
