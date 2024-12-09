@extends('layouts.app')

@section('content')
    <h1>SuperAdmin Dashboard</h1>
    <p>Welcome, SuperAdmin!</p>

    <!-- ユーザーの役割を表示 -->
    <p>Role: {{ Auth::user()->role->name }}</p>

    <!-- ユーザー作成リンク -->
    <div class="mt-4">
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">
            Create New User
        </a>
    </div>
@endsection
