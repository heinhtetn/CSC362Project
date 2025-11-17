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
                    <form id="modalForm" method="POST" class="inline">
                        @csrf
                        <button id="modalConfirm" type="submit"
                            class="px-4 py-2 text-white rounded-lg transition-colors">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmation Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const modalForm = document.getElementById('modalForm');
            const modalConfirm = document.getElementById('modalConfirm');
            const modalCancel = document.getElementById('modalCancel');

            // Handle all confirmation triggers
            document.querySelectorAll('[data-confirm]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('form');
                    const title = this.getAttribute('data-confirm-title') || 'Confirm Action';
                    const message = this.getAttribute('data-confirm') || 'Are you sure?';
                    const type = this.getAttribute('data-confirm-type') || 'danger';
                    const confirmText = this.getAttribute('data-confirm-text') || 'Confirm';

                    // Set modal content
                    modalTitle.textContent = title;
                    modalMessage.textContent = message;
                    modalConfirm.textContent = confirmText;

                    // Set icon based on type
                    let iconHtml = '';
                    let confirmClass = '';
                    if (type === 'danger' || type === 'delete') {
                        iconHtml =
                            '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                        confirmClass = 'bg-red-600 hover:bg-red-700';
                    } else if (type === 'warning' || type === 'suspend') {
                        iconHtml =
                            '<svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                        confirmClass = 'bg-orange-600 hover:bg-orange-700';
                    } else if (type === 'success' || type === 'activate' || type === 'accept') {
                        iconHtml =
                            '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        confirmClass = 'bg-green-600 hover:bg-green-700';
                    } else {
                        iconHtml =
                            '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        confirmClass = 'bg-blue-600 hover:bg-blue-700';
                    }

                    modalIcon.innerHTML = iconHtml;
                    modalConfirm.className = 'px-4 py-2 text-white rounded-lg transition-colors ' +
                        confirmClass;

                    // Clone form data
                    modalForm.action = form.action;
                    modalForm.method = form.method;

                    // Store the confirm button text and class before clearing
                    modalForm.innerHTML = '';

                    // Add CSRF token first
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = form.querySelector('input[name="_token"]')?.value || '';
                    modalForm.appendChild(csrfInput);

                    // Copy all form inputs (hidden, textarea, select, and other inputs)
                    Array.from(form.elements).forEach(element => {
                        if (element.name && element.type !== 'submit' && element.type !==
                            'button') {
                            const clone = element.cloneNode(true);

                            // Handle different input types
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                if (element.checked) {
                                    clone.style.display = 'none';
                                    modalForm.appendChild(clone);
                                }
                            } else {
                                // For textarea, select, hidden, and text inputs
                                if (element.tagName === 'TEXTAREA' || element.tagName ===
                                    'SELECT') {
                                    clone.style.display = 'none';
                                } else if (element.type !== 'hidden') {
                                    clone.style.display = 'none';
                                }
                                modalForm.appendChild(clone);
                            }
                        }
                    });

                    // Create and add the confirm button with proper text and styling
                    const confirmBtn = document.createElement('button');
                    confirmBtn.type = 'submit';
                    confirmBtn.id = 'modalConfirm';
                    confirmBtn.textContent = confirmText;
                    confirmBtn.className = 'px-4 py-2 text-white rounded-lg transition-colors ' +
                        confirmClass;
                    modalForm.appendChild(confirmBtn);

                    // Update the reference for future use
                    const newConfirmButton = document.getElementById('modalConfirm');

                    // Show modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Close modal handlers
            modalCancel.addEventListener('click', function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    </script>

</body>

</html>
