<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <style>
    @media (max-width: 1200.98px) {
        .w-25 {
            width: 100% !important;
            }
        .mx-5{
            margin:5px 0 5px 0 !important;
        }
        }
        .pagination{
            justify-content: center;
        }
    </style>
        @if($alerts->count() !== 0)
        <div class="mx-5 card shadow bg-warning p-3 w-25 d-inline-flex">
            <div class="card" style="height:250px;">
                <span class="m-1 p-1 bg-info rounded mx-auto mt-1" style="color:white;font-size:20px;text-align:center;width:40%; height:40px">Alerts({{$alerts->count()}})</span>
                @foreach($alerts as $alert)
                <a class="m-3" href="{{route('alerts.edit', $alert)}}">{{$alert->name}}</a><br>
                @endforeach
          </div>
        </div>
        @endif
        <div class="mx-5 card shadow bg-light p-3 w-25 d-inline-flex" >
            <div class="card" style="height:250px;">
                <span class="m-1 p-1 bg-info rounded mx-auto mt-1" style="color:white;font-size:20px;text-align:center;width:40%; height:40px">Tasks</span>
                <div class="w-100 px-5 py-2 border-bottom"><span class="mx-auto my-2 float-left">Completed tasks:</span><span class="bg-success rounded p-1 text-dark float-right" style="text-align:center; width:25%;">{{$completedtasks}}</span></div>
                <div class="w-100 px-5 py-2 border-bottom mt-1"><span class="mx-auto my-2">Pending tasks:</span><span class="ml-2 bg-warning rounded p-1 text-dark float-right" style="text-align:center; width:25%;">{{$pendingtasks}}</span></div>
                <div class="w-100 px-5 py-2 mt-1"><span class="mx-auto my-2">Active tasks:</span><span class="ml-2 bg-info rounded p-1 text-white float-right" style="text-align:center; width:25%;">{{$activetasks}}</span></div>
                <a href="{{route('tasks.index')}}" class="btn btn-dark w-50 mx-auto my-1">Go</a>
            </div>

        </div>
        <div class="mx-5 card shadow bg-light p-3 w-25 d-inline-flex" >
            <div class="card" style="height:250px;">
            <span class="m-1 p-1 bg-info rounded mx-auto mt-1" style="color:white;font-size:20px;text-align:center;width:40%; height:40px">Recent Posts</span>
                   <div class="" style="height:180px;">
                    @foreach($posts as $post)
                    <div style="height:18.5%" class="w-100 px-5 border-bottom"><span class="mx-auto my-1"><a style="text-align:center;" href="{{route('posts.show', $post->id)}}">{{$post->title}}</a></span></div>
                    @endforeach
                    </div>
                    <a href="{{route('posts.index')}}" class="btn btn-dark w-50 mx-auto">Go</a>
            
            </div>

    <!--<script> WEATHER API
        $.ajax({
            url: "http://api.openweathermap.org/data/2.5/weather",
            dataType: "json",
            method: 'get',
            data:{[
                id: 524901,
                appid: 'b72b6cf5efaa01c23a6a3fd19903ed9a', 
                city: 'Penzance'
                ]},
            success: function(result){
                console.log(result)
            },
            error: function(error){
                console.log(error)
            }
        })
    </script>-->
</x-app-layout>