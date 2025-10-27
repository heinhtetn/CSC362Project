@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Jobs List</h2>
        <a href="{{ route('jobs.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Job</a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Title</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Location</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Salary</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($jobs as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $job->id }}</td>
                        <td class="px-4 py-2">{{ $job->title }}</td>
                        <td class="px-4 py-2">{{ $job->location }}</td>
                        <td class="px-4 py-2">{{ $job->salary }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <a href="{{ route('jobs.edit', $job) }}" class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('jobs.destroy', $job) }}" method="POST"
                                onsubmit="return confirm('Delete this job?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
@endsection
