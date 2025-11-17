<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label>Email</label>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
            </div>
            <button class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Login</button>
        </form>
        <p class="mt-4 text-center">Back to <a href="{{ route('landing') }}"
                class="text-blue-500 hover:underline">Home</a></p>
    </div>

</body>

</html>
