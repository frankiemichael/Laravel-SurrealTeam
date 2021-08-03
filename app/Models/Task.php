<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'priority',
        'deadline',
        'occurrence',
        'completed',
        'completedby',
        'pending',
        'daily',
        'weekly',
        'img_path',
        'site',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('task_id', 'user_id');
    }
    public function notes()
    {
        return $this->hasMany(TaskNote::class, 'task_id');
    }
}