@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-bold text-lg mb-4">Users</h2>
            <p class="text-gray-600">Total Users: {{ \App\Models\User::count() }}</p>
            <a href="{{ route('admin.users.index') }}" class="text-blue-500 mt-2 inline-block hover:underline">Manage
                Users</a>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-bold text-lg mb-4">Jobs</h2>
            <p class="text-gray-600">Total Jobs: {{ \App\Models\Job::count() }}</p>
            <a href="{{ route('admin.jobs.index') }}" class="text-blue-500 mt-2 inline-block hover:underline">Manage Jobs</a>
        </div>
    </div>
@endsection
