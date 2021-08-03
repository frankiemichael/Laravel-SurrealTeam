<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create User
        </h2>
    </x-slot>
    <x-jet-validation-errors />
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('users.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                        <h3 class="mt-5 ml-5">Name</h3>
                        <div class="form-group">
                            <input value="{{ old('name', '') }}" type="text" name="name" class="form-control" id="name">
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <h3 class="mt-5 ml-5">Email</h3>
                        <div class="form-group">
                            <input value="{{ old('email', '') }}" type="email" name="email" class="form-control" id="email">
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <h3 class="mt-5 ml-5">Password</h3>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" id="password">
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <h3 class="mt-5 ml-5">Roles</h3>
                        <div class="form-group">
                            <select name="roles[]" class="form-control" id="roles" multiple>
                            @foreach($roles as $id => $role)
                                <option value="{{ $id }}">{{ in_array($id, old('roles', [])) ? ' selected' : '' }}>{{ $role }}</option>
                            @endforeach
                            </select>
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="btn btn-primary">
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
