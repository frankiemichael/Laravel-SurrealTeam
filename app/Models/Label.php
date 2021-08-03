<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(LabelRequest::class, 'order_id');
    }

    public function product()
    {
        return $this->hasMany(TremenheereStock::class, 'id', 'product_id');
    }

    public function variant()
    {
        return $this->hasMany(ProductVariants::class, 'id', 'variant_id');
    }
}
