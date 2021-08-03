<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
    public function type()
    {
        return $this->hasMany(PostType::class);
    }
    public function likes()
    {
        return $this->belongsToMany(User::class, 'like_post_user', 'post_id', 'user_id');
    }
    public function dislikes()
    {
        return $this->belongsToMany(Post::class, 'dislike_post_user', 'post_id', 'user_id');
    }

}
