<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
        </h2>
    </x-slot>
    <x-jet-validation-errors />
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                         <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="form-group">
                            <h3 class="mt-5 ml-5">Name</h3>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" />
                                @error('name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        

                        <div class="form-group">
                        <h3 class="mt-5 ml-5">Email</h3>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" />
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                        <h3 class="mt-5 ml-5">Base Site</h3>
                            <select name="site" class="form-control">
                                <option value="{{$user->site}}">No change</option>
                                <option value="Trewidden">Trewidden</option>
                                <option value="Trereife">Trereife</option>
                                <option value="Tremenheere">Tremenheere</option>
                            </select>
                            @error('site')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="roles" class="block font-medium text-sm text-gray-700">Roles</label>
                            <select name="roles[]" id="roles" class="custom-select" multiple="multiple">
                                @foreach($roles as $id => $role)
                                    <option value="{{ $id }}"{{ in_array($id, old('roles', $user->roles->pluck('id')->toArray())) ? ' selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="btn btn-primary">
                                Edit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>