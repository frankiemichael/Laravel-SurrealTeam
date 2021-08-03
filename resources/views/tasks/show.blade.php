<x-app-layout>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Show Task
        </h2>

    </x-slot>

    <x-jet-validation-errors />
    @include('tasks.actionbar')
    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
        
            <div class="d-flex">
                <div class="p-2">

                </div>
                <div class="ml-auto p-2">
                <form class="" action="{{ route('tasks.complete', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('put')
                    <input type="text" name="completed" id="completed" value="{{$task->completed}}" hidden>
                    @if($task->completed == 0)
                    <input type="submit" class="completebutton btn btn-success" value="Complete">
                    @else
                    <input type="submit" class="completebutton btn btn-warning" value="Uncomplete">
                    @endif
                </form> 
                </div>
                </div>
            <br>
            @if ($task->img_path !== NULL)

                @if ($extension === 'mp4' or $extension === 'mov')
                <video src="{{$task->img_path}}" title="{{$task->name}}" width="320" height="240" controls playsinline type='video/mov'></video>
                @else
                <img src="{{$task->img_path}}" alt="{{$task->name}}" class="mb-5 rounded mx-auto d-block" width="50%">    
                @endif   
            @endif
            <div class="flex flex-col">

            <div class="container p-3 my-3 border">
                <h1>{{$task->name}}</h1>
                <p>{{$task->description}}</p>
                <p style="font-size:12px" class="font-italic">
                <p style="font-size:12px" class="font-weight-bold">@if ($task->deadline != null)Deadline - {{date('l jS F Y', strtotime($task->deadline))}}
                @endif</p>

            </div>
            <div class="container-sm">
            <div class="row">
                <div class="col-sm">
                    <h3>Priority</h3>
                    {{$task->priority}}
                </div>
                <div class="col-sm">
                    <h3>Occurrence</h3>
                    {{$task->occurrence}}
                </div>
                <div class="col-sm">
                    <h3>Created</h3>
                    {{date('l jS F Y', strtotime($task->created_at))}}
                </div>
                <div class="col-sm">
                    <h3>Set for</h3>
                    @foreach ($task->users as $user)
                    {{$user->name}}<br>
                    @endforeach
                </div>
                <div class="col-sm">
                    <h3>Site</h3>
                    {{$task->site}} 
                                      
                </div>
                @if($task->completed === 1)
                <div class="col-sm">
                    <h3>Completed</h3>
                    {{date('l jS F Y', strtotime($task->updated_at))}} - by {{$task->completedby}}
                </div>
                @endif
            </div>
        </div> 
    </div>

    <div id="overlay">
        <div class="container bg-info p-5" style="margin-top:200px;width:100%;">
        <div style="width:25px;height:25px;z-index:100;" class="close-window float-right"><i class="far fa-window-close"></i></div>
        <h2>Request a change</h2>
        <form action="{{route('tasks.requestchange', $task->id)}}" method="POST">
        @csrf
            <div class="form-group">
            <label for="type">Request type</label>
                <select name="request_type" class="form-control" id="type">
                    <option>Delay</option>
                    <option>Change staff</option>
                    <option>Other issues</option>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Further notes</label>
                <textarea class="form-control" name="note" id="note" required></textarea>
            </div>
            <button type="submit" id="{{$task->id}}" class="submitrequest btn btn-dark">Submit Request</button>
        </div>
        </form>
    </div>
    <div id="overlay2">
    
        <div class="container bg-info p-5" style="margin-top:200px;">
        <div style="width:25px;height:25px;z-index:100;" class="close-window float-right"><i class="far fa-window-close"></i></div>
            <h2>Request History</h2>
            @foreach($task->notes as $note)
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">On <b>{{date('l jS F Y', strtotime($note->created_at))}}</b> by <b>{{$note->users->name}}</b></h4>
                <p>Request type: <b>{{$note->request_type}}</b></p>
                <hr>
                <p class="mb-2">{{$note->note}}</p>
            </div>
            @endforeach

        </div>
    </div>
    <script>
    $(document).ready(function(){
        function on() {
            document.getElementById("overlay").style.display = "block";
        }

        function off() {
            document.getElementById("overlay").style.display = "none";
        }
        function on2() {
            document.getElementById("overlay2").style.display = "block";
        }

        function off2() {
            document.getElementById("overlay2").style.display = "none";
        }
        $('.requesthistory').on('click', function(){
            on2()
        
        })

        $('.requests').on('click', function(){
            on()
        
        })
        $('.close-window').hover(
        function(){
            $(this).find('i').addClass('fas')
            $(this).find('i').removeClass('far')
    },
        function(){
            $(this).find('i').removeClass('fas')
            $(this).find('i').addClass('far')
    })
    $('.close-window').on('click', function(){
        off2()
        off()
    })




        $(document).mouseup(function(e) {
            var container = $("#overlay").find('.container');
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                off();
                $('#tBody').empty()
            }
        });
        $(document).mouseup(function(e) {
            var container = $("#overlay2").find('.container');
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                off2();
            }
        });
    })
    $('.deletebutton').on('click', function(e){
        e.preventDefault()
        if(confirm("Are you sure?")){
            $(this).parent('form').submit()
        }else{
            return false
        }
    })
    </script>
</x-app-layout>
