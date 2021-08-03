<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('My Tasks') }}
        </h2>

    </x-slot>
    <x-jet-validation-errors />
    

            @include('tasks.actionbar')
            
            <h4 class="my-3">Site</h4>
            <select id="siteselect" class="form-control">
                <option>All sites</option>
                <option value="Trereife">Trereife</option>
                <option value="Trewidden">Trewidden</option>
                <option value="Tremenheere">Tremenheere</option>
            </select>
            <h4 class="my-2">Occurrence</h4>
            <select id="occurrenceselect" class="form-control">
                <option>All</option>
                <option value="Never">Never</option>
                <option value="Daily">Daily</option>
                <option value="Weekly">Weekly</option>
            </select>
    <table class="table table-striped">
        <thead>
            <tr>
            <th class="d-none d-lg-table-cell"></th>
            <th scope="col" width="7.5%"><a href="{{ route('tasks.index') }}?occurrence={{$currentoccurrence}}&column=deadline&ascdesc={{$switch}}">Deadline</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="10%">Set for</th>
            <th scope="col" width="15%"><a href="{{ route('tasks.index') }}?occurrence={{$currentoccurrence}}&column=name&ascdesc={{$switch}}">Name</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="25%"><a href="{{ route('tasks.index') }}?occurrence={{$currentoccurrence}}&column=description&ascdesc={{$switch}}">Description</a></th>
            <th scope="col" width="10%"><a href="{{ route('tasks.index') }}?occurrence={{$currentoccurrence}}&column=priority&ascdesc={{$switch}}">Priority</a></th>
            <th scope="col" width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
        
        @if ($mytasks->count() === 0)
        <tr>
            <td colspan="100">No tasks to display.</td>
        </tr>
        @endif
        @foreach ($mytasks as $task)
            
            <tr>
            <td class="d-none d-lg-table-cell">
            @if ($task->img_path !== NULL)
                @if (pathinfo($task->img_path, PATHINFO_EXTENSION) === 'mp4' or pathinfo($task->img_path, PATHINFO_EXTENSION) === 'mov')
                <video src="{{$task->img_path}}" alt="{{$task->name}}" class="mb-5 rounded mx-auto d-block" width="80%">    
                @else
                <img src="{{$task->img_path}}" alt="{{$task->name}}" class="mb-5 rounded mx-auto d-block" width="50%">    
                @endif 
            @else
            <img src="/images/logocut.png" alt="Surreal Succulents" width="50%">
            @endif
            </td>
            <th scope="row" class="deadlinetd">
            @if($task->deadline !== NULL)
            <span class="deadlinehour">{{ date('H', strtotime($task->deadline))}}</span>:<span class="deadlineminute">{{ date('i', strtotime($task->deadline))}}</span><br><br><span class="deadlineday">{{ date('d', strtotime($task->deadline))}}</span>-<span class="deadlinemonth">{{ date('m', strtotime($task->deadline))}}</span>
            @elseif($task->daily !== NULL)
            <span class="deadlinehour">{{ date('H', strtotime($task->daily))}}</span>:<span class="deadlineminute">{{ date('i', strtotime($task->daily))}}</span><br><br><span class="deadlineday">{{ date('d', strtotime($task->daily))}}</span>-<span class="deadlinemonth">{{ date('m', strtotime($task->daily))}}</span>
            @elseif($task->weekly !== NULL)
            <span class="deadlinehour">{{ date('H', strtotime($task->weekly))}}</span>:<span class="deadlineminute">{{ date('i', strtotime($task->weekly))}}</span><br><br><span class="deadlineday">{{ date('d', strtotime($task->weekly))}}</span>-<span class="deadlinemonth">{{ date('m', strtotime($task->weekly))}}</span>
            @endif
            </th>
            <td class="d-none d-lg-table-cell">
            @if($task->users->count() === 1 && $task->users[0]->id === Auth::user()->id)
                Just me
            @elseif($task->users[0]->id === 999999999)
            Everyone
            @else
            <a class="seemore" href="">More details...</a>
            <span hidden>
            @foreach ($task->users as $user)
                {{$user->name}} <br>
            @endforeach
            </span>
            @endif
            
            </td>
            <td><a href="{{ route('tasks.show', $task->id) }}" >{{$task->name}}</a></td>
            <td class="d-none d-lg-table-cell">{{$task->description}}</td>
            <td>{{$task->priority}}</td>
            <td>
            <form class="inline-block" action="{{ route('tasks.complete', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
                    @method('put')
                <input type="text" name="completed" id="completed" value="{{$task->completed}}" hidden>

                <input type="submit" class="completebutton btn btn-success w-100" value="Complete">
            </form>          
            </td>
            </tr>
        @endforeach


        

        </tbody>
        </table>
        {{ $mytasks->appends($_GET)->links() }}
        
        <div id="overlay">
            <div style="margin-top:200px;" class="container bg-info">
                <table class="table table-striped" style="background-color:white;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">

                    </tbody>
                </table>
            </div>
        </div>
        <script type="text/javascript">
        function on() {
            document.getElementById("overlay").style.display = "block";
        }

        function off() {
            document.getElementById("overlay").style.display = "none";
        }
            $('.seemore').on('click', function(e){
                e.preventDefault()
                alert($(this).next('span').text())
            })
            
            $('.pendingtasks').on('click', function(e){
                e.preventDefault()
                var token = $('meta[name="csrf-token"]').attr('content')
                var url = $(this).attr("href")
                on()
                $.ajax({
                    url: url,
                    method:"GET",
                    data:{_token:token},
                    success:function(result){
                        var task = result
                        $.each(task, function(i, val){
                            var route = "{{ route('tasks.show', ':id')}}"
                            route = route.replace(':id', val.id)
                            $('#tBody').append('<tr><td><a href="' + route + '">' + val.name + '</td><td>' + val.description + '</td><td><button id="' + val.id + '" class="btnaccept btn btn-success">Accept</button><button id="' + val.id + '" class="btndecline ml-5 btn btn-danger">Decline</button></td>')


                        })
                        $('.btndecline').on('click', function(e){
                                e.preventDefault()
                                var id = $(this).attr("id")
                                var url1 = "{{ route('tasks.accept', ':id')}}"
                                url1 = url1.replace(':id', id)

                                var note = prompt('Reason for declining this task:')
                                $.ajax({
                                    method:"PATCH",
                                    url: url1,
                                    data:{_token:token, note:note, pending:1},
                                    success: function(result){
                                        $('#tBody').empty()
                                        off()
                                        if($('.flashalert').is(':animated')){
                                            return false;
                                        }else{
                                        $('.flashalert').attr('hidden', false);
                                        $('.flashalert').find('span').text(result)
                                        $('.flashalert').fadeIn().delay('1500').fadeOut('slow')
                                        
                                        }
                                    },
                                    error: function(error){
                                        console.log(error)
                                    }
                                })
                            })

                            $('.btnaccept').on('click', function(e){
                                e.preventDefault()
                                var id = $(this).attr("id")
                                var url1 = "{{ route('tasks.accept', ':id')}}"
                                url1 = url1.replace(':id', id)

                                $.ajax({
                                    method:"PATCH",
                                    url: url1,
                                    data:{_token:token, pending:0},
                                    success: function(result){
                                        console.log(result)
                                        off()
                                        if($('.flashalert').is(':animated')){
                                            return false;
                                        }else{
                                        
                                        $('.flashalert').attr('hidden', false);
                                        $('.flashalert').find('span').text(result)
                                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                                        }
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);
                                    },
                                    error: function(error){
                                        console.log(error)
                                    }
                                })
                            })
                    },
                    error: function(error){
                        console.log(error)
                    }
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
            })
            var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
        function getQueryVariable(url, variable) {
            var query = url.substring(1);
            var vars = query.split('&');
            for (var i=0; i<vars.length; i++) {
                var pair = vars[i].split('=');
                if (pair[0] == variable) {
                    return pair[1];
                }
            }

            return false;
        }
            $('.seemore').on('click', function(e){
                e.preventDefault()
                alert($(this).next('span').text())
            })
            var site = getUrlParameter('site')
            if(site == false){
                $('#siteselect').val($('#siteselect option:first').val())

            }else{
                $('#siteselect').val(site)

            }
            $('#siteselect').on('change', function(){
                var site = $(this).val()
                if(site !== "All sites"){
                    var url = "{{route('tasks.index')}}"
                    $.ajax({
                        url: url,
                        data:{site:site},
                        success:function(result){
        
                            if(!getUrlParameter('site') && !getUrlParameter('occurrence')){
                                window.location.href = window.location.href + "?site=" + site
                            }else if(getUrlParameter('site') && !getUrlParameter('occurrence')){
                                var params = {'site': site}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 
                            }else if(getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'site': site, 'occurrence': getUrlParameter('occurrence')}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 

                            }else if(!getUrlParameter('site') && getUrlParameter('occurrence')){
                                var params = {'site': site,  'occurrence': getUrlParameter('occurrence')}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 
                            }
                        }
                    })
                }else{
                    if(getUrlParameter('occurrence')){
                        var params = {'occurrence': getUrlParameter('occurrence')}
                        window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params)
                    }else{
                        window.location.href = "{{route('tasks.index')}}"
                    }                
                }
            })
            var occurrence = getUrlParameter('occurrence')
            if(occurrence == false){
                $('#occurrenceselect').val($('#occurrenceselect option:first').val())
            }else{
                $('#occurrenceselect').val(occurrence)

            }
            $('#occurrenceselect').on('change', function(){
                
                var occurrence = $(this).val()
                if(occurrence !== "All"){
                    var url = "{{route('tasks.index')}}"
                    $.ajax({
                        url: url,
                        data:{occurrence:occurrence},
                        success:function(result){
                            if(!getUrlParameter('site') && !getUrlParameter('occurrence')){
                                window.location.href = window.location.href + "?occurrence=" + occurrence
                            }else if(getUrlParameter('occurrence') && !getUrlParameter('site')){
                                var params = {'occurrence': occurrence}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 
                            }else if(getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'occurrence': occurrence, 'site': getUrlParameter('site')}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 

                            }else if(!getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'occurrence': occurrence,  'site': getUrlParameter('site')}
                                window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params) 
                            }
                        }
                    })
                }else{
                    if(getUrlParameter('site')){
                        var params = {'site': getUrlParameter('site')}
                        window.location.href = "{{route('tasks.index')}}?" + jQuery.param(params)
                    }else{
                        window.location.href = "{{route('tasks.index')}}"
                    }
                }
            })

        </script>
</x-app-layout>