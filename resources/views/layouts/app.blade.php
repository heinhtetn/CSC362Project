<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 text-xl font-bold border-b">Admin Panel</div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-bold' : '' }}">Dashboard</a>
                <a href="{{ route('users.index') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('users.*') ? 'bg-gray-100 font-bold' : '' }}">Users</a>
                <a href="{{ route('jobs.index') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('jobs.*') ? 'bg-gray-100 font-bold' : '' }}">Jobs</a>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <!-- Top bar -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">@yield('title')</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>

            <!-- Content -->
            @yield('content')
        </div>
    </div>

</body>

</html>
