<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Team Overview') }}
        </h2>


    </x-slot>

    <x-jet-validation-errors />
    @foreach($users as $user)
    <h2>{{$user->name}}</h2>
    <table class="table table-striped mb-5">
  <thead>
    <tr>
      <th width="20%" class="d-none d-lg-table-cell"></th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>
  @foreach($user->tasks as $task)
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
    <td><a href="{{ route('tasks.show', $task->id) }}" >{{$task->name}}</a></td>
    <td>{{$task->description}}</td>
    </tr>
    @endforeach

  </tbody>
</table>
    @endforeach

</x-app-layout>