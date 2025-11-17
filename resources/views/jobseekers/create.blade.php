<!DOCTYPE html>
<html>

<head>
    <title>Create Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 items-center justify-center h-screen flex">

    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Complete Your Profile</h1>

        @if (session('warning'))
            <p class="text-yellow-600 mb-3">{{ session('warning') }}</p>
        @endif
        <form method="POST" action="{{ route('jobseeker.store') }}" enctype="multipart/form-data">
            @csrf

            <label class="block mb-2">Full Name</label>
            <input type="text" name="full_name" class="w-full border p-2 rounded" required>

            <label class="block mt-4 mb-2">Phone</label>
            <input type="text" name="phone" class="w-full border p-2 rounded">

            <label class="block mt-4 mb-2">Resume (PDF only)</label>
            <input type="file" name="resume" class="w-full border p-2 rounded" accept="application/pdf">


            <button class="mt-4 w-full bg-blue-500 text-white p-2 rounded">
                Save Profile
            </button>
        </form>
    </div>

</body>

</html>
