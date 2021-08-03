<x-app-layout>
<x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Alerts') }}
        </h2>
    </x-slot>
    <h1>{{$alert->name}}</h1>
    <p>{{$alert->description}}</p>
    <form action="{{route('alerts.destroy', $alert)}}" method="POST">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-info">Delete</button>
    </form>
</x-app-layout> 
