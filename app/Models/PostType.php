<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class PostType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function user()
    {
        return $this->belongsToMany(User::class, 'poll_user_votes', 'poll_id', 'user_id');
    }
    public function vote()
    {
        return $this->belongsToMany(User::class, 'poll_user_votes', 'poll_id', 'user_id')->wherePivot('user_id', Auth::id());
    }

}
