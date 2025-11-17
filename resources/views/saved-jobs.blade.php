<!DOCTYPE html>
<html>

<head>
    <title>Saved Jobs - JobHunter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

    @include('partials.header')

    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Your Saved Jobs</h1>

        @if (session('success'))
            <p class="p-3 bg-green-200 text-green-800 mb-4 rounded">
                {{ session('success') }}
            </p>
        @endif

        @if ($savedJobs->isEmpty())
            <p class="text-gray-500">You have no saved jobs.</p>
        @else
            <div class="space-y-4">
                @foreach ($savedJobs as $job)
                    <div class="p-4 bg-white shadow rounded flex justify-between">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $job->title }}</h3>
                            <p class="text-gray-700">{{ $job->company }}</p>
                            <p class="text-gray-500 text-sm mb-2">{{ $job->location }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="/jobs/{{ $job->id }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">View</a>

                            <form method="POST" action="{{ route('jobseeker.unsavejob', $job->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    data-confirm="Are you sure you want to remove this job from your saved jobs?"
                                    data-confirm-title="Remove Saved Job" data-confirm-type="warning"
                                    data-confirm-text="Remove"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('partials.confirm-modal')
</body>

</html>
