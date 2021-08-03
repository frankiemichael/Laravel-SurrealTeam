<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class GeneralController extends Controller
{
    public function dashboard()
    {
        $alerts = Alert::orderBy('created_at')->paginate(5);
        $posts = Post::with('comments')->orderBy('created_at')->paginate(5);
        $completedtasks = Task::where('completed', 1)->where('completedby', Auth::user()->name)->count();
        $pendingtasks = Task::with('users')->whereHas('users', function($q){
            $q->where('task_user.user_id', Auth::id())->orWhere('task_user.user_id', 1);
        })->where('pending', 1)->where('completed', 0)->count();
        $activetasks = Task::with('users')->whereHas('users', function($q){
            $q->where('task_user.user_id', Auth::id())->orWhere('task_user.user_id', 1);
        })->where('pending', 0)->where('completed', 0)->count();
        return view('dashboard', compact('posts', 'alerts', 'completedtasks', 'pendingtasks', 'activetasks'));
    }

    public function fileauth()
    {
        $fileauth = Storage::disk('local')->get('.well-known/pki-validation/fileauth.txt');
        return $fileauth;
    }

    public function index()
    {
        return view('public.index');
    }
}