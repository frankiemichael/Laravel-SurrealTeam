<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Posts') }}
        </h2>
    </x-slot>
    <x-jet-validation-errors />

    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Post</div>
                <div class="card-body">

                    @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <form method="post" action="{{ route('posts.store') }}">
                        <div class="form-group">
                            @csrf
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="posttype form-control" name="type" id="type">
                                    <option value="1">Post</option>
                                    <option value="2">Poll</option>
                                    <option value="3">Alert (pinned post)</option>
                                </select> 
                            </div>
                            <input type="text" value="{{Auth::user()->id}}" name="user_id" hidden>
                            <label class="label">Title: </label>
                            <input type="text" name="title" class="form-control" required/>
                            <label class="label">Description: </label>
                            <textarea type="text" name="description" class="form-control" required></textarea>
                            <div class="optiondiv form-group" hidden>
                                <label for="type">Options</label>
                                <input type="text" name="option[]" class="optioninput form-control"/>
                                <a class="addoption" href=""><i class="fas fa-plus"> Add Option</i></a>
                            </div>
                            <div class="prioritydiv form-group" hidden>
                                <label for="type">Priority</label>
                                <select name="priority" class="priorityinput form-control">
                                    
                                    <option value="3">Low</option>
                                    <option value="2">Medium</option>
                                    <option value="1">High</option>

                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Create post"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>