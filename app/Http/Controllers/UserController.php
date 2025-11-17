<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
            'active' => 'nullable|boolean',
        ]);

        User::create($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
            'active' => 'nullable|boolean',
        ]);

        $data = $request->only('name', 'email', 'role', 'active');
        if ($request->password) $data['password'] = $request->password;

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function suspend(User $user)
    {
        $user->update(['active' => false]);
        return redirect()->route('admin.users.index')->with('success', 'User suspended successfully.');
    }

    public function activate(User $user)
    {
        $user->update(['active' => true]);
        return redirect()->route('admin.users.index')->with('success', 'User activated successfully.');
    }
}
