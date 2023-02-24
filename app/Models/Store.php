<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function get_by_store_hash($store_hash)
    {
        return self::where('store_hash', $store_hash)->first();
    }
}
