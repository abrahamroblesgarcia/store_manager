<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    public function store()
    {
        return $this->belongsTo(Store::class)->first();
    }

    public static function get_by_product_hash($product_hash)
    {
        return self::where('product_hash', $product_hash)->first();
    }

    public static function get_all_by_store($store_id)
    {
        return self::where('store_id', $store_id)->get();
    }
}
