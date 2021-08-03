<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function postlike($id)
    {
        $post = Post::where('id', $id)->with('likes')->first();
        $post->likes()->attach($id, [
            'user_id' => Auth::user()->id
        ]); 
    }

    public function postdislike($id)
    {
        $post = Post::where('id', $id)->with('dislikes')->first();
        $post->dislikes()->attach($id, [
            'user_id' => Auth::user()->id
        ]); 
    }

    public function postunlike($id)
    {

       $post = Post::where('id', $id)->with('likes')->first();
       $post->likes()->wherePivot('user_id', Auth::user()->id)->wherePivot('post_id', $id)->detach();
    }

    public function postundislike($id)
    {
        $post = Post::where('id', $id)->with('dislikes')->first();
        $post->dislikes()->wherePivot('user_id', Auth::user()->id)->wherePivot('post_id', $id)->detach();
 
    }
}
