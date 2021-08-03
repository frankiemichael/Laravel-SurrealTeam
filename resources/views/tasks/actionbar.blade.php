@if(Route::current()->getName() == 'tasks.show')
<div class="second-nav float-right" style="width:auto !important;" >
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Quick Actions
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="ml-2 requests">Request a change</a>
            <a class="ml-2 requesthistory">Request History ({{$task->notes->count()}})</a>
            @if(Gate::allows('admin_access') || $task->creator_id === Auth::id())

            <a href="{{route('tasks.edit', $task)}}" class="ml-2 edittask">Edit Task</a>
            
            @endif

        </div>
    </div>
</div>
@else
<div class="second-nav float-right" style="width:auto !important;" >
    <div class="input-group">

            @if($pendingcount > 0)
            <a href="{{ route('tasks.pending') }}" class="pendingtasks btn btn-warning mr-1">Pending({{$pendingcount}})</a>
            @endif
        <a href="{{ route('tasks.create') }}" class="btn btn-success">+</a>
    </div>
</div>
@endif
