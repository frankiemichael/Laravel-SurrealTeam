<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNote extends Model
{
    protected $fillable = [
        'task_id',
        'note',
        'request_type',
        'user_id'
    ];

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
