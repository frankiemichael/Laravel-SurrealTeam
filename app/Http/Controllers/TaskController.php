<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\ServiceProvider;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\ProgressListener\AbstractProgressListener;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\ProgressListenerDecorator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $column = isset($request['column']) ? ($request['column']) : "deadline";
        $ascdesc = isset($request['ascdesc']) ? ($request['ascdesc']) : "desc";
        if ($request['ascdesc'] == 'asc'){
            $switch = 'desc';
            $sort = "sortByDesc";
        }else{
            $switch = 'asc';
            $sort = "sortBy";
        }
        if ($request['occurrence'] == null){
            $occurrence = '%';
        }else{
            $occurrence = $request['occurrence'];
        }
        if($request['pending']){
            $pending = 1;
        }else{
            $pending = 0;
        }
        if($request->site){
            $site = $request->site;
        }else{
            $site = '%';
        }
        $currentoccurrence = $occurrence;
        $userid = Auth::id();
        $tasks = Task::with('users')->whereHas('users', function($q){
            $q->where('task_user.user_id', Auth::id())->orWhere('task_user.user_id', 1);
        });
        $mytasks = $tasks->where('pending', $pending)->where('occurrence', 'LIKE', $occurrence)->where('site', 'LIKE', $site)->where('completed', 0)->orderBy($column, $ascdesc)->paginate(10);
        $pendingcount = Task::with('users')->whereHas('users', function($q){
            $q->where('task_user.user_id', Auth::id())->orWhere('task_user.user_id', 1);
        })->where('completed', 0)->where('pending', 1)->count();

        return view('tasks.index', compact('mytasks', 'currentoccurrence', 'switch', 'pendingcount'));

        

    }
    public function alltasks(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $column = isset($request['column']) ? ($request['column']) : "deadline";
        $ascdesc = isset($request['ascdesc']) ? ($request['ascdesc']) : "desc";
        if ($request['ascdesc'] == 'asc'){
            $switch = 'desc';
        }else{
            $switch = 'asc';
        }
        if ($request['occurrence'] == ''){
            $occurrence = 'Never';
        }else{
            $occurrence = $request['occurrence'];
        }   
        if($request['pending']){
            $pending = 1;
        }else{
            $pending = 0;
        }
        if($request['site']){
            $site = $request['site'];
        }else{
            $site = '%';
        }
        $currentoccurrence = $occurrence;
        
        $alltasks = Task::with('notes')->where('site', 'LIKE', $site)->where('occurrence', $occurrence)->orderBy($column, $ascdesc)->with('users')->paginate(10);  
        
        return view('tasks.alltasks', compact('alltasks', 'currentoccurrence', 'switch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users') );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        

        if ($request->has('img_path')){
            $mime = pathinfo($request->file('img_path')->getClientOriginalExtension());
            if($mime['filename'] === "MOV" || $mime['filename'] === "mov" || $mime['filename'] === "mp4" || $mime['filename'] === "3gp" || $mime['filename'] === "flv"){
                $file = $request['img_path']->getClientOriginalName();
                $extension = $request['img_path']->getClientOriginalExtension();
                $filename = 'vid-' . pathinfo($file, PATHINFO_FILENAME) . date('his');
                $storagePath = 'images/tasks';
                $tempPath = 'images/temp';

                $request['img_path']->storeAs($tempPath, $filename);
                $format = new X264('libmp3lame', 'libx264');
                $decoratedFormat = ProgressListenerDecorator::decorate($format);
                $video = FFMpeg::fromDisk('local')->open($tempPath . '/' . $filename)
                ->export()
                ->toDisk('local')
                ->inFormat($decoratedFormat)
                ->onProgress(function () use ($decoratedFormat) {
                    $listeners = $decoratedFormat->getListeners();  // array of listeners
            
                    $listener = $listeners[0];  // instance of AbstractProgressListener
            
                    $listener->getCurrentPass();
                    $listener->getTotalPass();
                    $listener->getCurrentTime();

                })
                ->save($storagePath . '/' . $filename . ".mp4");
                $img_path = $storagePath . '/' . $filename . ".mp4";
            }else if($mime['basename'] === "png" || $mime['basename'] === "jpg" || $mime['basename'] === "jpeg" || $mime['basename'] === "gif"){
                $img_path = $request->file('img_path')->storeAs('images/tasks', 'img-' . $request->file('img_path')->getClientOriginalName() . date('his'));
            }
        }else{
            $img_path = NULL;

        }
        $task = new Task([
            $request->validated(),
            'creator_id' => Auth::user()->id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'priority' => $request->get('priority'),
            'deadline' => $request->get('deadline'),
            'weekly' => $request->get('weekly'),
            'daily' => $request->get('daily'),
            'occurrence' => $request->get('occurrence'),
            'img_path' => $img_path,
            'site' => $request->site,
        ]);
       
        $task->save();
        $task->users()->sync($request->input('staff', []));
        
        return redirect()->route('tasks.index')->with('success', 'Task has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $taskid = $task->id;
        $task = Task::where('id', $taskid)->with('users')->first();
        $extension = pathinfo($task->img_path, PATHINFO_EXTENSION);
    
        return view('tasks.show', compact('task', 'extension'));

    }

    public function pending(Request $request)
    {
        $task = Task::whereHas('users', function($q) {
            $q->where('task_user.user_id', Auth::id())->orWhere('task_user.user_id', '1');
        })->with('users');

        $pendingtasks = $task->where('pending', 1)->get();
        return response()->json($pendingtasks);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $users = User::all();
        $task = Task::where('id', $task->id)->with('users')->first();
        $staff = json_encode($task->users);
        return view('tasks.edit', compact('task', 'users', 'staff'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->has('img_path')){
            $mime = pathinfo($request->file('img_path')->getClientOriginalExtension());
            
            if($mime == "mov" or "mp4" or "3gp" or "flv"){
                $file = $request['img_path']->getClientOriginalName();
                $extension = $request['img_path']->getClientOriginalExtension();
                $filename = 'vid-' . pathinfo($file, PATHINFO_FILENAME) . date('his');
                $storagePath = 'images/tasks';
                $tempPath = 'images/temp';

                $request['img_path']->storeAs($tempPath, $filename);
                $format = new X264('libmp3lame', 'libx264');
                $decoratedFormat = ProgressListenerDecorator::decorate($format);
                $video = FFMpeg::fromDisk('local')->open($tempPath . '/' . $filename)
                ->export()
                ->toDisk('local')
                ->inFormat($decoratedFormat)
                ->onProgress(function () use ($decoratedFormat) {
                    $listeners = $decoratedFormat->getListeners();  // array of listeners
            
                    $listener = $listeners[0];  // instance of AbstractProgressListener
            
                    $listener->getCurrentPass();
                    $listener->getTotalPass();
                    $listener->getCurrentTime();

                })
                ->save($storagePath . '/' . $filename . ".mp4");
                $img_path = $storagePath . '/' . $filename . ".mp4";
            }else if($mime == "png" or "jpg" or "jpeg" or "gif"){
                $img_path = $request->file('img_path')->storeAs('images/tasks', 'img-' . $request->file('img_path')->getClientOriginalName() . date('his'));
            }
        }else{
            $img_path = NULL;

        }
        $task = Task::find($request->id);
        $task->update([
            'name' => $request->name,
            'creator_id' => Auth::user()->id,
            'description' => $request->description,
            'priority' => $request->priority,
            'occurrence' => $request->occurrence,
            'deadline' => $request->deadline,
            'img_path' => $img_path,
            'site' => $request->site,
        ]);
        $task->save();
        $task->users()->sync($request->input('staff', []));

        return redirect()->route('tasks.edit', $task->id)->with('success', 'Task has been edited.');

    }

    public function accept(Request $request)
    {
        
        $task = Task::find($request->id);
        if($request->pending == 1){
            $task->notes()->create([
                'note' => $request->note,
                'task_id' => $request->id,
                'user_id' => Auth::user()->id,
            ]);
            return 'Task declined. Reason: ' . $request->note;
            }else{
            $task->update([
                'pending' => 0,
            ]);
            
            return 'Task accepted.';

        }
        
    }

    public function requestchange(Request $request)
    {
        $task = Task::find($request->id);
        $task->notes()->create([
                'task_id' => $request->id,
                'request_type' => $request->request_type,
                'note' => $request->note,
                'user_id' => Auth::user()->id,
            ]);
        $task->update([
            'pending' => 1,
        ]);
        return redirect()->route('tasks.show', $task)->with('success', 'Request sent.');
    }

    public function complete(Request $request)
    {
        if($request['completed'] == 1){
            $set = 0;
        }else{
            $set = 1;
        }        

        Task::where('id', $request['id'])->update([
            'completed' => $set,
            'completedby' => Auth::user()->name,
        ]);
        return redirect()->route('tasks.index');
    }

    public function teamoverview()
    {
        
        $users = User::where('id', '!=', Auth::user()->id)->with('tasks')->get();
        
        return view('tasks.teamoverview', compact('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if(Gate::denies('admin_access') || $task->creator_id !== Auth::id()){
            return redirect()->back()->with('error', 'You do not have the permission to do this.');
        }
        $task->delete();
        $task->users()->detach();
        return redirect()->route('tasks.index')->with('success', 'Task has been successfully deleted.');
    }

}
