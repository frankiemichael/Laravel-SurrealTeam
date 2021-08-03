<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\PostType;
use Illuminate\Support\Facades\Auth;
use App\Models\Alert;

class PostController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('comments')->with('likes')->with('dislikes')->with('type')->paginate(5);

        return view('post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
        ]);

        if ($validator->fails()) {

            return redirect('post')
                        ->withErrors($validator)
                        ->withInput();
        }
        if($request->type !== '3'){
            $post = new Post([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'slug' => \Str::slug($request->title)
            ]);
            $post->save();
            if ($request->type == 2){
                foreach($request->option as $option){
                    $type = new PostType([
                        'post_id' => $post->id,
                        'option' => $option
                    ]);
                    $type->save();
                }
            }
        }else{  
            $post = new Alert([
                'name' => $request->title,
                'user_id' =>$request->user_id,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);
            $post->save();
        }
        return redirect()->route('posts.index')->with('success', 'Post has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $vote = PostType::where('post_id', $post->id)->with('vote')->whereHas('vote', function($q) use($post){
            $q->where('poll_user_votes.user_id', Auth::id())->where('post_types.post_id', $post->id);
        })->get();
        
        $post = Post::where('id', $post->id)->with('type')->with('comments')->with('users')->with('comments.users')->first();
        return view('post.single',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Post::where('id', $id)->with('type.user')->whereHas('type.user', function($q){
            $q->where('poll_user_votes.user_id', Auth::id());
        })->get();
        if($validate->count() !== 0){
            return redirect()->back()->with('error', 'You have already voted.');
        }
        $post = PostType::where('id', $request->vote)->with('user')->first();
        $post->user()->attach($request->vote, ['user_id' => Auth::user()->id]);
        return redirect()->back()->with('success', 'You have voted for ' . $post->option . ".");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
    /*    $comments = Comment::where('commentable_id', $id)->with('replies')->get();

        foreach($comments as $comment){
            if(count($comment->replies)){
                $comment->replies->detach();
                $comment->delete();
        
            }else{
                $comment->delete();
            }
        }
        */
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post has been deleted');
    }
}
