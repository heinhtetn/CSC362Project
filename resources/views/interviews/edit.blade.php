@extends('layouts.app')

@section('title', 'Edit Interview Schedule')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.applications.show', $application) }}" class="text-blue-600 hover:underline">← Back to
            Application</a>
    </div>

    <div class="bg-white rounded shadow p-6 max-w-lg">
        <h2 class="text-2xl font-bold mb-4">Edit Interview Schedule</h2>

        <form action="{{ route('admin.interviews.update', $application) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                <input type="datetime-local" name="scheduled_at" class="w-full border rounded px-3 py-2"
                    value="{{ old('scheduled_at', \Carbon\Carbon::parse($interview->scheduled_at)->format('Y-m-d\TH:i')) }}"
                    required>

            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="location" class="w-full border rounded px-3 py-2"
                    value="{{ old('location', $interview->location) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $interview->notes) }}</textarea>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update
                Interview</button>
        </form>
    </div>
@endsection
