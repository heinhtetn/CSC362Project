<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 text-xl font-bold border-b">Admin Panel</div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-bold' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 font-bold' : '' }}">Users</a>
                <a href="{{ route('admin.jobs.index') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('admin.jobs.*') ? 'bg-gray-100 font-bold' : '' }}">Jobs</a>
                <a href="{{ route('admin.applications.index') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('admin.applications.*') ? 'bg-gray-100 font-bold' : '' }}">
                    Applications
                    @php
                        $pendingCount = \App\Models\Application::whereHas('job', function ($q) {
                            $q->where('admin_id', \Illuminate\Support\Facades\Auth::guard('admin')->id());
                        })
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span
                            class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.documentation') }}"
                    class="block px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('admin.documentation') ? 'bg-gray-100 font-bold' : '' }}">Documentation</a>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 p-6">
            <!-- Top bar -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">@yield('title')</h1>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" data-confirm="Are you sure you want to logout from the admin panel?"
                        data-confirm-title="Logout" data-confirm-type="warning" data-confirm-text="Logout"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>

            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="modalIcon" class="flex-shrink-0 mr-3"></div>
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                </div>
                <p id="modalMessage" class="text-gray-600 mb-6"></p>
                <div class="flex justify-end space-x-3">
                    <button id="modalCancel" type="button"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button id="modalConfirm" type="button" class="px-4 py-2 text-white rounded-lg transition-colors">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmation Modal Handler ONLY
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const modalConfirm = document.getElementById('modalConfirm');
            const modalCancel = document.getElementById('modalCancel');
            let activeForm = null;

            document.querySelectorAll('[data-confirm]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    activeForm = this.closest('form');

                    modalTitle.textContent = this.dataset.confirmTitle || 'Confirm Action';
                    modalMessage.textContent = this.dataset.confirm || 'Are you sure?';
                    modalConfirm.textContent = this.dataset.confirmText || 'Confirm';

                    // Set icon and button color
                    const type = this.dataset.confirmType || 'warning';
                    let iconHtml = '';
                    let confirmClass = '';
                    if (type === 'danger' || type === 'delete') {
                        iconHtml =
                            '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.06 19h15.88c1.54 0 2.5-1.667 1.73-3L13.73 4c-.77-1.333-2.69-1.333-3.46 0L2.33 16c-.77 1.333.19 3 1.73 3z"/></svg>';
                        confirmClass = 'bg-red-600 hover:bg-red-700';
                    } else if (type === 'warning') {
                        iconHtml = '<svg class="w-6 h-6 text-orange-600" ...></svg>';
                        confirmClass = 'bg-orange-600 hover:bg-orange-700';
                    } else {
                        iconHtml = '<svg class="w-6 h-6 text-blue-600" ...></svg>';
                        confirmClass = 'bg-blue-600 hover:bg-blue-700';
                    }
                    modalIcon.innerHTML = iconHtml;
                    modalConfirm.className = 'px-4 py-2 text-white rounded-lg ' + confirmClass;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            modalConfirm.addEventListener('click', function() {
                if (activeForm) {
                    activeForm.submit();
                    activeForm = null;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });

            modalCancel.addEventListener('click', function() {
                activeForm = null;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    activeForm = null;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    </script>

</body>

</html>
