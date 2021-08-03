<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(Label::class, 'order_id');
    }
}
