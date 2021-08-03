<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class TremenheereStock extends Model
{
    use HasFactory;
    protected $guarded = [

    ];
    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariants::class, 'product_id');
    }

    public function parent()
    {
        return $this->hasOne(Categories::class, 'id', 'parent_id');
    }
}
