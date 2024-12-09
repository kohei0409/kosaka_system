<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Group;

class UserController extends Controller
{
    public function create()
    {
        $currentUserRole = auth()->user()->role->name;

        // Admin は SuperAdmin を表示しない
        $roles = Role::when($currentUserRole === 'Admin', function ($query) {
            $query->where('name', '!=', 'SuperAdmin');
        })->get();

        $groups = Group::all();

        return view('users.create', compact('roles', 'groups'));
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        // Admin が SuperAdmin を選択する場合はエラー
        $currentUserRole = auth()->user()->role->name;
        if ($currentUserRole === 'Admin') {
            $selectedRole = Role::find($request->role_id);
            if ($selectedRole && $selectedRole->name === 'SuperAdmin') {
                return redirect()->back()->withErrors(['role_id' => 'You do not have permission to assign this role.']);
            }
        }

        // ユーザーの作成
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => $request->role_id,
            'group_id' => $request->group_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'User created successfully.');
    }
}
