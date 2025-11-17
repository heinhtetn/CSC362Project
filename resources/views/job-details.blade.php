<!DOCTYPE html>
<html>

<head>
    <title>{{ $job->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    @include('partials.header')

    <div class="max-w-3xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-2">{{ $job->title }}</h1>
        <p class="text-gray-700 mb-1">{{ $job->company }}</p>
        <p class="text-gray-500 mb-2">{{ $job->location }}</p>
        <p class="text-gray-600 mb-4">Salary - {{ $job->salary }} ฿</p>

        <p class="mb-6">{{ $job->description }}</p>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 p-3 rounded mb-4">
                {{ session('info') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif


        @auth('web')
            <!-- Apply Button -->
            @if ($alreadyApplied)
                <button class="mt-6 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium" disabled>
                    Already Applied
                </button>
            @else
                <form method="POST" action="{{ route('jobs.apply', $job->id) }}"
                    class="mt-6 flex flex-col md:flex-row md:items-end md:space-x-3" enctype="multipart/form-data">
                    @csrf

                    <div class="flex-1 mb-3 md:mb-0">
                        <label class="block mb-2 font-semibold">Cover Letter (optional)</label>
                        <input type="file" name="cover_letter" class="w-full border p-2 rounded"
                            accept="application/pdf">
                    </div>

                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Apply Now
                    </button>
                </form>
            @endif

            <!-- Save Job Button -->
            <div class="mt-4">
                @if ($alreadySaved)
                    <button class="px-6 py-3 bg-gray-500 text-white rounded-lg cursor-not-allowed font-medium" disabled>
                        Already Saved
                    </button>
                @else
                    <form method="POST" action="{{ route('jobs.save', $job->id) }}">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium">
                            Save Job
                        </button>
                    </form>
                @endif
            </div>
        @else
            <div class="mt-6 bg-yellow-100 p-4 rounded">
                <p>
                    Please
                    <a href="{{ route('web.login') }}" class="text-blue-600 underline">login</a>
                    to apply or save this job.
                </p>
            </div>
        @endauth

    </div>

    @include('partials.confirm-modal')
</body>

</html>
