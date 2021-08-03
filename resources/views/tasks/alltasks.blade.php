<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('All Tasks') }}
        </h2>

    </x-slot>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary ml-5">Add Task</a>
    <x-jet-validation-errors />
        <style>
        .troverlay {
            position: absolute;
            background: rgba(255,191,0,0.5);
            left: 0.7em;
            right: 0.7em;
            height: 100%;
            text-align: center;
            
            }

            tr {
            position: relative;
            }
        </style>
            <h4 class="my-3">Site</h4>
            <select id="siteselect" class="form-control">
                <option>All sites</option>
                <option value="Trereife">Trereife</option>
                <option value="Trewidden">Trewidden</option>
                <option value="Tremenheere">Tremenheere</option>
            </select>
            <h4 class="my-3">Occurrence</h4>
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
            <th scope="col" width="12.5%"><a href="{{ route('tasks.alltasks') }}?occurrence={{$currentoccurrence}}&column=deadline&ascdesc={{$switch}}">Deadline</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="10%">Set for</th>
            <th scope="col" width="15%"><a href="{{ route('tasks.alltasks') }}?occurrence={{$currentoccurrence}}&column=name&ascdesc={{$switch}}">Name</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="25%"><a href="{{ route('tasks.alltasks') }}?occurrence={{$currentoccurrence}}&column=description&ascdesc={{$switch}}">Description</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="8%"><a href="{{ route('tasks.alltasks') }}?occurrence={{$currentoccurrence}}&column=priority&ascdesc={{$switch}}">Priority</a></th>
            <th scope="col" width="2%"><a href="{{ route('tasks.alltasks') }}?occurrence={{$currentoccurrence}}&column=completed&ascdesc={{$switch}}">Status</a></th>
            <th scope="col" width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
        @if ($alltasks->count() == 0)
        <tr>
            <td colspan="100">No tasks to display.</td>
        </tr>
        @endif

        @foreach ($alltasks as $task)
        
            @if($task->pending === 0 && $task->completed === '0')
            <style>
            .tablestripe {
                border-color:#ffbf00 !important;
            }
            </style>
            <tr class="tablestripe">
            @else
            <tr>
            @endif
            
            <td class="d-none d-lg-table-cell">
            @if ($task->img_path !== NULL)
                <img src="{{$task->img_path}}" alt="{{$task->name}}" width="60px">
            @else
                <img src="/images/logocut.png" alt="Surreal Succulents" width="60x">
            @endif
            </td>
            <th scope="row" class="deadlinetd"><span class="deadlinehour">{{ date('H', strtotime($task->deadline))}}</span>:<span class="deadlineminute">{{ date('i', strtotime($task->deadline))}}</span><br><br><span class="deadlineday">{{ date('d', strtotime($task->deadline))}}</span>-<span class="deadlinemonth">{{ date('m', strtotime($task->deadline))}}</span></th>
            <td class="d-none d-lg-table-cell">
            @if($task->users->count() === 0)
                Noone
            @else
            @if($task->users[0]->id === 1)
                Everyone
                @elseif($task->users->count() > 1 )
                <a class="seemore" href="">More details...</a>
                <span hidden>
                @foreach ($task->users as $user)
                    {{$user->name}} <br>
                @endforeach
                </span>
                @elseif($task->users->count() === 1)
                {{$task->users[0]->name}}
                @endif
            @endif
            </td>
            <td><a href="{{ route('tasks.show', $task->id) }}">{{$task->name}}</a></td>

            <td class="d-none d-lg-table-cell">{{$task->description}}</td>
            <td class="d-none d-lg-table-cell">{{$task->priority}}</td>
            <td>
            @if($task->completed === 1)
            <span style="color:green"><i class="fas fa-check"></i></span>
            @elseif($task->completed === 0 && $task->pending === 1)
            <span style="color:red"><i class="fas fa-times"></i></span>
            @elseif($task->completed === 0 && $task->pending === 0)
            <span style="color:orange"><i class="fa fa-spinner" aria-hidden="true"></i></i></span>
            @endif
            </td>
            <td>
            <form class="inline-block" action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger w-100" value="Delete">
            </form>          
            </td>

            @if($task->notes->count() > 0 && $task->pending === 1 && $task->completed === 0)
            <td class="troverlay"><b><a href="{{route('tasks.show', $task->id)}}" style="color:black;font:20px;">Change requested<a></b></td>
            @endif

            </tr>
        @endforeach


        

        </tbody>
        </table>
        {{ $alltasks->appends($_GET)->links() }}

        <script type="text/javascript">
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
                    var url = "{{route('tasks.alltasks')}}"
                    $.ajax({
                        url: url,
                        data:{site:site},
                        success:function(result){
                            if(!getUrlParameter('site') && !getUrlParameter('occurrence')){
                                window.location.href = window.location.href + "?site=" + site
                            }else if(getUrlParameter('site') && !getUrlParameter('occurrence')){
                                var params = {'site': site}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 
                            }else if(getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'site': site, 'occurrence': getUrlParameter('occurrence')}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 

                            }else if(!getUrlParameter('site') && getUrlParameter('occurrence')){
                                var params = {'site': site,  'occurrence': getUrlParameter('occurrence')}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 
                            }
                        }
                    })
                }else{
                    if(getUrlParameter('occurrence')){
                        var params = {'occurrence': getUrlParameter('occurrence')}
                        window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params)
                    }else{
                        window.location.href = "{{route('tasks.alltasks')}}"
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
                    var url = "{{route('tasks.alltasks')}}"
                    $.ajax({
                        url: url,
                        data:{occurrence:occurrence},
                        success:function(result){
                            if(!getUrlParameter('site') && !getUrlParameter('occurrence')){
                                window.location.href = window.location.href + "?occurrence=" + occurrence
                            }else if(getUrlParameter('occurrence') && !getUrlParameter('site')){
                                var params = {'occurrence': occurrence}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 
                            }else if(getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'occurrence': occurrence, 'site': getUrlParameter('site')}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 

                            }else if(!getUrlParameter('occurrence') && getUrlParameter('site')){
                                var params = {'occurrence': occurrence,  'site': getUrlParameter('site')}
                                window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params) 
                            }
                        }
                    })
                }else{
                    if(getUrlParameter('site')){
                        var params = {'site': getUrlParameter('site')}
                        window.location.href = "{{route('tasks.alltasks')}}?" + jQuery.param(params)
                    }else{
                        window.location.href = "{{route('tasks.alltasks')}}"
                    }
                }
            })

        </script>
</x-app-layout>