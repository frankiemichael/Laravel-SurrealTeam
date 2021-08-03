<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Task
        </h2>


    </x-slot>

    <x-jet-validation-errors />
    <style>
        .overlay{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255,255,255,0.8) url("{{asset('/storage/images/spinner.gif')}}") center no-repeat;
        }
        /* Turn off scrollbar when body element has the loading class */
        body.loading{
            overflow: hidden;   
        }
        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay{
            display: block;
        }
    </style>
    <div>
    
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                        <h3 class="mt-5 ml-5">Name</h3>
                        <div class="form-group">
                            <input value="{{ old('name', '') }}" type="text" name="name" class="form-control" id="name" placeholder="Task Name" required>
                            @error('name')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <h3 class="mt-5 ml-5">Description</h3>
                        <div class="form-group">
                            <textarea value="{{ old('description', '') }}" type="text" name="description" class="form-control" placeholder="Description"></textarea>
                            @error('description')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                        <h3 class="mt-5 ml-5">Priority</h3>
                            <select class="form-control" name="priority">
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                            </select>
                            @error('priority')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                        <h3 class="mt-5 ml-5">Occurrence</h3>
                            <select class="occurrence form-control" name="occurrence">
                                <option>Never</option>
                                <option>Daily</option>
                                <option>Weekly</option>
                            </select>

                        <div class="deadlinediv">
                        <h3 class="mt-5 ml-5"><span class='deadlinetitle'>Deadline</span></h3>

                            <input type="datetime-local" class="time" name="deadline" required>
                            @error('deadline')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <script type="text/javascript">
                        $(document).ready(function(){
                            $('.occurrence').on('change', function(){
                                var occur = $(this).val();
                                if (occur == 'Never'){
                                    $('.deadlinetitle').text('Deadline')
                                    $('.time').attr('name', 'deadline')
                                    $('.time').attr('type', 'datetime-local')

                                }else if (occur == 'Daily') {
                                    $('.deadlinetitle').text('Reset every')                            
                                    $('.time').attr('name', 'daily')
                                    $('.time').attr('type', 'time')

                                }else if (occur == 'Weekly'){
                                    $('.time').attr('type', 'datetime-local')
                                    $('.deadlinetitle').text('Weekly deadline')                            
                                    $('.time').attr('name', 'weekly')

                                }
                            })
                        })
                        </script>

                        </div>
                        <h3 class="mt-5 ml-5">Staff</h3>
                            <select name="staff[]" multiple required>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            @error('staff')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror

                            <h3 class="mt-5 ml-5">Site</h3>
                            <select name="site" class="form-control">
                                <option value="Trewidden">Trewidden</option>
                                <option value="Trereife">Trereife</option>
                                <option value="Tremenheere">Tremenheere</option>
                            </select>
                            @error('site')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                            
                            @can('developer_access')

                        
                        <h3 class="mt-5 ml-5">Upload media</h3>
                        <input type="file" name='img_path' value="NULL">
                        @error('img_path')
                            <div class="alert alert-danger" role="alert">
                                {{$message}}
                            </div>
                            @enderror
                            @endcan

                        
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="btncreate btn btn-primary">
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="overlay"></div>
</x-app-layout>
