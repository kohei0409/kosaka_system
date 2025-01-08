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
                   <th class="py-3 px-2 border">CODE</th>
                <th class="py-3 px-2 border">名前</th>
                <th class="py-3 px-2 border">メールアドレス</th>
                <th class="py-3 px-2 border">ロール</th>
                <th class="py-3 px-2 border">作成日</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
            <tr
                class="table-row border-t text-gray-800"
                onclick="window.location='{{ route('users.show', $user->id) }}'"
            >
                <td class="py-3 px-2 border" style="height: 50px">{{ $user->id }}</td>
                  <td class="py-3 px-2 border">{{ $user->Code }}</td>
                <td class="py-3 px-2 border">{{ $user->name }}</td>
                <td class="py-3 px-2 border">{{ $user->email }}</td>
                <td class="py-3 px-2 border">{{ $user->role->name }}</td>
                <td class="py-3 px-2 border">{{ $user->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-3 px-4 border text-center text-gray-800">ユーザーが見つかりません。</td>
            </tr>
            @endforelse
            </tbody>


        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
