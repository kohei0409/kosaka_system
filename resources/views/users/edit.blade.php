@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-lg font-bold mb-6">ユーザーを編集</h1>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="Code" class="block text-sm font-medium text-white-700">コード</label>
            <input type="text" name="Code" id="Code" value="{{ old('Code', $user->Code) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-white-700">名前</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white-700">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        </div>

        <div class="mb-4">
            <label for="role_id" class="block text-sm font-medium text-white-700">ロール</label>
            <select name="role_id" id="role_id" class="mt-1 block w-full border-gray-300 rounded-md text-gray-900"
                    required>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" @if ($user->role_id == $role->id) selected @endif>{{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="bg-warning bg-indigo-600 text-black px-4 py-2 rounded-md hover:bg-indigo-500">
            ユーザー情報を更新する
        </button>
    </form>

    <form class="text-end" method="POST" action="{{ route('users.update', $user->id) }}" style="margin-top: 100px">
        @csrf
        @method('PATCH')
        <input type="hidden" name="Code" id="Code" value="0"
               class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>
        <input type="hidden" name="name" id="name" value="{{ old('name', $user->name) }}"
               class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>

        <input type="hidden" name="email" id="email" value="{{ old('email', $user->email) }}"
               class="mt-1 block w-full border-gray-300 rounded-md text-gray-900" required>

        <input type="hidden" name="role_id" id="role_id" value="{{ $user->role_id }}">
        <button style="background-color: #444" type="submit" class="btn brn-sm bg-indigo-600 text-black px-2 py-1 rounded-md hover:bg-indigo-500">
            ユーザーを削除する
        </button>
    </form>
</div>
@endsection
