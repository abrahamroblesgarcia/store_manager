<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Contexts\Product as ProductContext;
use Illuminate\Http\Request;

class StoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => ['sometimes', 'unique:stores,name'],
            'products' => ['sometimes'],
            'products.*.product_hash' => ['required', 'distinct', 'exists:products,product_hash',
            function (string $attribute, mixed $value, $fail) use ($request) {
                $product = new ProductContext();
                if (!$product->is_product_of_store($value, $request->store_hash)) {
                    $fail("{$attribute} {$value} is invalid or dosen't belongs to selected store");
                }
            }],
            'products.*.name' => ['sometimes', 'distinct'],
            'products.*.stock' => ['sometimes', 'integer', 'min:0']
        ];
    }
}
