<!DOCTYPE html>
<html>

<head>
    <title>JobHunter - Jobs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

    @include('partials.header')


    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Available Jobs</h1>

        <div class="space-y-4">
            @foreach ($jobs as $job)
                <div class="p-4 bg-white shadow rounded">
                    <h3 class="text-xl font-semibold">{{ $job->title }}</h3>
                    <p class="text-gray-700">{{ $job->company }}</p>
                    <p class="text-gray-500 text-sm mb-2">{{ $job->location }}</p>
                    <p class="mb-4">{{ $job->description }}</p>
                    <a href="/jobs/{{ $job->id }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">View</a>
                </div>
            @endforeach
        </div>
    </div>

    @include('partials.confirm-modal')

</body>

</html>
