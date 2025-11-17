<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

        @if (session('success'))
            <p class="text-green-700 mb-3">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('jobseeker.update') }}" enctype="multipart/form-data">
            @csrf

            <label class="block mb-2">Full Name</label>
            <input type="text" name="full_name" value="{{ $profile->full_name }}" class="w-full border p-2 rounded"
                required>

            <label class="block mt-4 mb-2">Phone</label>
            <input type="text" name="phone" value="{{ $profile->phone }}" class="w-full border p-2 rounded">

            <label class="block mt-4 mb-2">Resume (PDF only)</label>
            <input type="file" name="resume" class="w-full border p-2 rounded" accept="application/pdf">
            @if ($profile->resume)
                <p class="text-green-600 mt-2">
                    Current Resume:
                    <a href="{{ asset('storage/' . $profile->resume) }}" class="text-blue-500 underline"
                        target="_blank">
                        View PDF
                    </a>
                </p>
            @endif

            <button class="mt-4 w-full bg-blue-500 text-white p-2 rounded">
                Update Profile
            </button>
        </form>
    </div>

</body>

</html>
