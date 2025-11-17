@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Users List</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add
            User</a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Role</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Active</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->id }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->role }}</td>
                        <td class="px-4 py-2">
                            @if ($user->active)
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="text-blue-500 hover:underline">Edit</a>
                                @if ($user->active)
                                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            data-confirm="Are you sure you want to suspend this user? They will not be able to access their account."
                                            data-confirm-title="Suspend User" data-confirm-type="suspend"
                                            data-confirm-text="Suspend"
                                            class="text-orange-500 hover:underline">Suspend</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            data-confirm="Are you sure you want to activate this user? They will regain access to their account."
                                            data-confirm-title="Activate User" data-confirm-type="activate"
                                            data-confirm-text="Activate"
                                            class="text-green-500 hover:underline">Activate</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
