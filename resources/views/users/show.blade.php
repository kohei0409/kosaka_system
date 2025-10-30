@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-lg font-bold">ユーザー詳細</h1>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500">
                ユーザーを編集
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded p-6">
        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">Code</h2>
            <p class="text-gray-800">{{ $user->Code }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">名前</h2>
            <p class="text-gray-800">{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">メールアドレス</h2>
            <p class="text-gray-800">{{ $user->email }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">ロール</h2>
            <p class="text-gray-800">{{ $user->role ? $user->role->name : '未設定' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">グループ</h2>
            <p class="text-gray-800">{{ $user->group->name ?? 'なし' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-sm font-semibold text-gray-600">登録日</h2>
            <p class="text-gray-800">{{ $user->created_at->format('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection
