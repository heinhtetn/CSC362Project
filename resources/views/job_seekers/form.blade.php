@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Job Seeker Form</h2>

    <form method="POST"
        action="{{ isset($jobSeeker) ? route('job_seekers.update', $jobSeeker) : route('job_seekers.store') }}">
        @csrf
        @if (isset($jobSeeker))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="block mb-1">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $jobSeeker->full_name ?? '') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $jobSeeker->phone ?? '') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Resume (URL or text)</label>
            <textarea name="resume" class="w-full border rounded p-2">{{ old('resume', $jobSeeker->resume ?? '') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ isset($jobSeeker) ? 'Update' : 'Create' }}
        </button>
    </form>
@endsection
