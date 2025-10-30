<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

// ✅ これを追加
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // ✅ ユーザー一覧
    public function index()
    {
        $users = User::paginate(10); // ✅ ページネーションを有効化
        $roles = Role::all(); // ✅ 登録フォームに必要なデータを渡す
        return view('users.index', compact('users', 'roles'));
    }


    // ✅ ユーザー編集ページ
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

public function update(Request $request, $id)
{
    Log::info("update() メソッドが呼び出されました: ユーザーID = $id");

    $user = User::findOrFail($id);

    $validated = $request->validate([
        'Code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'role_id' => 'required|integer',
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    Log::info("リクエストされたパスワード: " . ($request->password ? '入力あり' : 'なし'));

    $user->Code = $validated['Code'];
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->role_id = $validated['role_id'];

    if (!empty($request->password)) {
        Log::info("パスワードを変更します: " . $user->email);

        // ✅ ここで `Hash::make()` を **確実に適用**
        $hashedPassword = Hash::make($request->password);
        Log::info("ハッシュ化後のパスワード: " . $hashedPassword);

        $user->password = $hashedPassword;
    } else {
        Log::info("パスワードは変更されませんでした: " . $user->email);
    }

    $user->save();

    Log::info("変更後のパスワードハッシュ: " . $user->password);

    return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました。');
}




}
