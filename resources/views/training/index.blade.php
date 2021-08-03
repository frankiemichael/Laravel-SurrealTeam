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
    <div class="row">
    @foreach($courses as $course)
    <div class="col-sm-6">
        <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{$course->id}} - {{$course->name}}</h5>
            <p class="card-text"></p>
            <a href="{{route('training.show', $course->id)}}" class="btn btn-primary">View Course</a>
        </div>
        </div>
    </div>
    @endforeach
    </div>
</x-app-layout>