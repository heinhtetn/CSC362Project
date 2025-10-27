@extends('layouts.app')

@section('title', 'Create Job')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg">
        <h2 class="text-xl font-bold mb-4">Create New Job</h2>

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border px-3 py-2 rounded"
                    required>
            </div>

            <div>
                <label class="block mb-1">Description</label>
                <textarea name="description" class="w-full border px-3 py-2 rounded" required>{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div>
                <label class="block mb-1">Salary</label>
                <input type="number" name="salary" step="0.01" value="{{ old('salary') }}"
                    class="w-full border px-3 py-2 rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Job</button>
        </form>
    </div>
@endsection
