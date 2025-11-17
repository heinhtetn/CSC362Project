@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.applications.index') }}" class="text-blue-600 hover:underline">← Back to Applications</a>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4">Application Details</h2>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Applicant Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $application->user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $application->user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                <p class="mt-1 text-sm text-gray-900">{{ $application->job->title }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Applied Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('F j, Y g:i A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>
        </div>

        {{-- Cover Letter File --}}
        @if ($application->cover_letter)
            <div class="mb-6 p-4 bg-gray-50 rounded border">
                <h3 class="font-semibold mb-2">Cover Letter</h3>
                <a href="{{ asset('storage/' . $application->cover_letter) }}" target="_blank"
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    View Cover Letter
                </a>
            </div>
        @endif

        {{-- Resume --}}
        @if ($resume)
            <div class="mb-6 p-4 bg-gray-50 rounded border">
                <h3 class="font-semibold mb-2">Resume</h3>
                <a href="{{ asset('storage/' . $resume) }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    View Resume
                </a>
            </div>
        @endif

        {{-- Scheduled Interview --}}
        @if ($application->interview)
            <div class="mb-6 p-4 bg-blue-50 rounded border border-blue-200 flex justify-between items-start">
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Scheduled Interview</h3>
                    <p class="text-sm text-blue-800">
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($application->interview->scheduled_at)->format('F j, Y g:i A') }}
                    </p>
                    @if ($application->interview->location)
                        <p class="text-sm text-blue-800"><strong>Location:</strong> {{ $application->interview->location }}
                        </p>
                    @endif
                    @if ($application->interview->notes)
                        <p class="text-sm text-blue-800 mt-2"><strong>Notes:</strong> {{ $application->interview->notes }}
                        </p>
                    @endif
                </div>
                <div>
                    <a href="{{ route('admin.interviews.edit', $application) }}"
                        class="px-3 py-1 text-sm bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
                </div>
            </div>
        @endif

        @if ($application->status === 'pending')
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-xl font-bold mb-4">Take Action</h3>
                <div class="space-y-4">
                    {{-- Schedule Interview --}}
                    <div class="border rounded p-4">
                        <h4 class="font-semibold mb-3">Schedule Interview</h4>
                        <form action="{{ route('admin.applications.schedule-interview', $application) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                                    <input type="datetime-local" name="scheduled_at" class="w-full border rounded px-3 py-2"
                                        value="{{ old('scheduled_at') }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <input type="text" name="location" class="w-full border rounded px-3 py-2"
                                        value="{{ old('location') }}" placeholder="e.g., Office, Online, etc.">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" placeholder="Additional info">{{ old('notes') }}</textarea>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Schedule
                                Interview</button>
                        </form>
                    </div>

                    {{-- Accept Application --}}
                    <div class="border rounded p-4">
                        <h4 class="font-semibold mb-3">Accept Application</h4>
                        <form action="{{ route('admin.applications.accept', $application) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Accept
                                Application</button>
                        </form>
                    </div>

                    {{-- Decline Application --}}
                    <div class="border rounded p-4">
                        <h4 class="font-semibold mb-3">Decline Application</h4>
                        <form action="{{ route('admin.applications.decline', $application) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason (Optional)</label>
                                <textarea name="reason" rows="2" class="w-full border rounded px-3 py-2" placeholder="Reason for declining">{{ old('reason') }}</textarea>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Decline
                                Application</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
