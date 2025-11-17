<!DOCTYPE html>
<html>

<head>
    <title>JobHunter - Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    @include('partials.header')

    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">

        <h2 class="text-2xl font-bold mb-4">Profile Details</h2>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('jobseeker.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="block mb-2">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}"
                class="w-full border p-2 rounded mb-4" required>

            <label class="block mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Resume (PDF)</label>
            @if (!empty($profile->resume))
                <p class="text-sm mb-2">Current: <a href="{{ asset('storage/' . $profile->resume) }}" target="_blank"
                        class="text-blue-600 underline">View Resume</a></p>
            @endif
            <input type="file" name="resume" accept="application/pdf" class="w-full border p-2 rounded mb-4">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Save Profile
            </button>
        </form>
    </div>

    @include('partials.confirm-modal')
</body>

</html>
