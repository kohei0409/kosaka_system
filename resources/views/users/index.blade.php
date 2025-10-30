@extends('layouts.app')

@section('content')

<style>
    .table-row {
        background-color: #f7fafc; /* デフォルトの背景色 */
        cursor: pointer;
        transition: background-color 0.5s ease-in-out;

    }

    .table-row:hover {
        background-color: #6b7280; /* ホバー時の背景色 */
        color: white;
    }
</style>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-lg font-bold mb-6 text-white-800">ユーザー一覧</h1> <!-- タイトルの文字色を修正 -->


    <div class="shadow-md rounded my-6">
        <table class="w-full table-auto border-collapse">
            <thead>
            <tr class="bg-gray-800 text-white"> <!-- ヘッダーは白文字 -->
                <th class="py-3 px-2 border" style="height: 40px">ID</th>
                <th class="py-3 px-2 border">ユーザーコード</th>
                <th class="py-3 px-2 border">名前</th>
                <th class="py-3 px-2 border">メールアドレス</th>
                <th class="py-3 px-2 border">ロール</th>
                <th class="py-3 px-2 border">作成日</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
            @if ($user->Code != 0)
            <tr class="table-row border-t text-gray-800 cursor-pointer"
                onclick="window.location='{{ route('users.show', $user->id) }}'">
                <td class="py-3 px-2 border text-center">{{ $user->id }}</td>
                <td class="py-3 px-2 border text-center">{{ $user->Code }}</td>
                <td class="py-3 px-2 border">{{ $user->name }}</td>
                <td class="py-3 px-2 border">{{ $user->email }}</td>
                <td class="py-3 px-2 border text-center">
                    {{ $user->role ? $user->role->name : '未設定' }}
                </td>
                <td class="py-3 px-2 border text-center">{{ $user->created_at->format('Y-m-d') }}</td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="6" class="py-3 px-2 border text-center">ユーザーが見つかりません</td>
            </tr>
            @endforelse

            </tbody>


        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>


</div>

<div class="container">
    <div class="row p-3" style="background-color:#999;max-width: 1000px;margin: 0 auto;">
        <h1 class="text-lg font-bold mb-6">ユーザー登録</h1>

        <style>
            .text-input {
                color: black; /* 入力文字の色を黒に設定 */
            }
        </style>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="row">


                <!-- 名前 -->
                <div class="col-6 mb-3">
                    <label for="name" class="form-label">名前</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <!-- メールアドレス -->
                <div class="col-6 mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <!-- パスワード -->
                <div class="col-6 mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <!-- パスワード（確認） -->
                <div class="col-6 mb-3">
                    <label for="password_confirmation" class="form-label">パスワード（もう一度）</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                           required>
                </div>

                <!-- 役割 -->
                <div class="col-6 mb-3">
                    <label for="role_id" class="form-label">役割</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">-- 役割を選択してください --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- ユーザーコード -->
                <div class="col-6 mb-3">
                    <label for="Code" class="form-label">ユーザーコード</label>
                    <input type="text" name="Code" id="Code" class="form-control" required>
                </div>
            </div>

            <!-- ボタン -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-light px-4 py-2">作成する</button>
            </div>
        </form>
    </div>
</div>
@endsection
