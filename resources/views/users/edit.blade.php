@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-lg font-bold mb-6">ユーザーを編集</h1>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-white-700">名前</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white-700">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        </div>

        <div class="mb-4">
            <label for="role_id" class="block text-sm font-medium text-white-700">ロール</label>
            <select name="role_id" id="role_id" class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @if ($user->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="group_id" class="block text-sm font-medium text-white-700">グループ</label>
            <select name="group_id" id="group_id" class="mt-1 block w-full border-gray-300 rounded-md text-gray-900">
                <option value="">-- Select Group --</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" @if ($user->group_id == $group->id) selected @endif>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500">更新</button>
    </form>
</div>
@endsection
