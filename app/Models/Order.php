<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator',
        'total',
        'notes',
        'email',
        'phone',
        'created_at',
        'updated_at',

    ];

    public function items()
    {
        return $this->belongsToMany(TremenheereStock::class, 'order_items');
    }

    public function paymentDetails()
    {
        return $this->hasOne(Payment::class);
    }
}
