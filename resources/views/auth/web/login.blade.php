<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - JobHunter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow items-center justify-center">
        <h1 class="text-2xl font-bold mb-4 text-center">Job Seeker Login</h1>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            <label class="block mb-2">Email</label>
            <input type="email" name="email" class="w-full p-2 border rounded mb-3" value="{{ old('email') }}" />

            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full p-2 border rounded mb-4" />

            <button class="px-6 py-2 bg-blue-600 text-white rounded w-full">Login</button>
        </form>

        <p class="text-sm text-center mt-4">Don't have an account? <a href="{{ route('web.register') }}"
                class="text-blue-600 hover:underline">Sign Up</a>
        </p>
    </div>
</body>

</html>
