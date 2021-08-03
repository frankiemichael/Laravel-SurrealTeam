<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Training') }}
        </h2>

    </x-slot>
    <x-jet-validation-errors />
    @include('training.actionbar')
    <form action="{{route('training.update', $course->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
    <label for="coursename">Name</label>
    <input class="form-control form-control-lg mb-3" type="text" value="{{$course->name}}" name="coursename" placeholder="Name">
    </div>
    <label for="simplemde">Course Details</label>
    <textarea name="markdown" id="simplemde">{{$course->markdown}}</textarea>
    <button class="btn btn-dark" type="submit">Submit Changes</button>
    </form>
    <script>
        var simplemde = new SimpleMDE({ element: $("#simplemde")[0] });
        var markdown = simplemde.value();
        var name = $('#coursename').val()
    </script>
</x-app-layout>