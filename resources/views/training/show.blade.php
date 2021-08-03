<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Training') }}
        </h2>

    </x-slot>
    <style>
        a{
            display: inline;
        }

    </style>
    <div class="second-nav">
            <a href="{{ route('training.edit', $course->id) }}" class="btn btn-info">Edit Course</a>

        </div>
    <x-jet-validation-errors />
    <div class="p-3 mb-5 bg-secondary text-white" style="text-align:center;"><h1>Training Course: {{$course->name}}</h1></div>
    <div style="max-width:100%; ">    
    @markdown($course->markdown)
    </div>

</x-app-layout>