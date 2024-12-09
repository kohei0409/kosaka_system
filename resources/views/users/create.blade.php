@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-lg font-bold mb-6">Create New User</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-white-700">Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-white-700">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-white-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="role_id" class="block text-sm font-medium text-white-700">Role</label>
            <select name="role_id" id="role_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                <option value="">-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="group_id" class="block text-sm font-medium text-white-700">Group</label>
            <select name="group_id" id="group_id" class="mt-1 block w-full border-gray-300 rounded-md">
                <option value="">-- Select Group --</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500">Create</button>
    </form>
</div>
@endsection
