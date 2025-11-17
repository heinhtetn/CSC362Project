<header class="p-4 bg-white shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="{{ route('jobs.list') }}" class="text-xl font-bold text-gray-900 hover:text-blue-600">
            JobHunter
        </a>

        <nav class="flex items-center space-x-4">
            @auth
                <a href="{{ route('jobs.list') }}"
                    class="text-gray-700 hover:text-blue-600 hover:underline {{ request()->routeIs('jobs.list') ? 'text-blue-600 font-semibold' : '' }}">
                    Jobs
                </a>
                <a href="/jobseeker/saved-jobs"
                    class="text-gray-700 hover:text-blue-600 hover:underline {{ request()->is('jobseeker/saved-jobs') ? 'text-blue-600 font-semibold' : '' }}">
                    Saved Jobs
                </a>
                <a href="/messages"
                    class="text-gray-700 hover:text-blue-600 hover:underline relative {{ request()->is('messages*') ? 'text-blue-600 font-semibold' : '' }}">
                    Messages
                    @if (isset($unreadMessageCount) && $unreadMessageCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}
                        </span>
                    @endif
                </a>
                <a href="/jobseeker/profile"
                    class="text-gray-700 hover:text-blue-600 hover:underline {{ request()->is('jobseeker/profile*') ? 'text-blue-600 font-semibold' : '' }}">
                    Profile
                </a>
                <form method="POST" action="{{ route('web.logout') }}" class="inline">
                    @csrf
                    <button type="submit" data-confirm="Are you sure you want to logout?" data-confirm-title="Logout"
                        data-confirm-type="warning" data-confirm-text="Logout"
                        class="text-red-600 hover:text-red-700 hover:underline font-medium">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('web.login') }}"
                    class="text-gray-700 hover:text-blue-600 hover:underline {{ request()->is('login') ? 'text-blue-600 font-semibold' : '' }}">
                    Login
                </a>
                <a href="{{ route('web.register') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors {{ request()->is('register') ? 'bg-blue-700' : '' }}">
                    Sign Up
                </a>
            @endauth
        </nav>
    </div>
</header>
