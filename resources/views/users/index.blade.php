<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <x-jet-validation-errors />
    <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Site</th>
                <th scope="col">Roles</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                <th scope="row">{{$user->id}}</th>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->site}}</td>
                <td>
                    @foreach ($user->roles as $role)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $role->title }}
                        </span>
                    @endforeach
                </td>
                <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                <form class="inline-block" action="{{ route('tasks.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>          
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            </x-app-layout>