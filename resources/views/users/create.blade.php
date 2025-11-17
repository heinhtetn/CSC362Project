@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg">
        <h2 class="text-xl font-bold mb-4">Create New User</h2>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded"
                    required>
            </div>

            <div>
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded"
                    required>
            </div>

            <div>
                <label class="block mb-1">Password</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1">Role</label>
                <select name="role" class="w-full border px-3 py-2 rounded" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="active" value="1" checked class="mr-2">
                <label>Active</label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create User</button>
        </form>
    </div>
@endsection
