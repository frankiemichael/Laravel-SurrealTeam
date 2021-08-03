<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $guarded = [];

    public function tremenheerestock()
    {
        return $this->hasMany(TremenheereStock::class, 'parent_id', 'id');
    }
    public function parent() {
        return $this->belongsTo('App\Models\Categories', 'parent_id');
    }
    public function children() {
        return $this->hasMany('App\Models\Categories', 'parent_id');
    }
}
