@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-bold text-lg mb-4">Users</h2>
            <p class="text-gray-600">Total Users: {{ \App\Models\User::count() }}</p>
            <a href="{{ route('admin.users.index') }}" class="text-blue-500 mt-2 inline-block hover:underline">Manage
                Users</a>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-bold text-lg mb-4">Jobs</h2>
            <p class="text-gray-600">Total Jobs:
                {{ \App\Models\Job::where('admin_id', \Illuminate\Support\Facades\Auth::guard('admin')->id())->count() }}
            </p>
            <a href="{{ route('admin.jobs.index') }}" class="text-blue-500 mt-2 inline-block hover:underline">Manage Jobs</a>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-bold text-lg mb-4">Applications</h2>
            @php
                $pendingCount = \App\Models\Application::whereHas('job', function ($q) {
                    $q->where('admin_id', \Illuminate\Support\Facades\Auth::guard('admin')->id());
                })
                    ->where('status', 'pending')
                    ->count();
            @endphp
            <p class="text-gray-600">Pending: <span class="font-bold text-red-600">{{ $pendingCount }}</span></p>
            <a href="{{ route('admin.applications.index') }}" class="text-blue-500 mt-2 inline-block hover:underline">View
                Applications</a>
        </div>
    </div>
@endsection
