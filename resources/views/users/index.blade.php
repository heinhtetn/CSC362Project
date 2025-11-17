@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Users List</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add User
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Role</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Active</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->id }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->role }}</td>
                        <td class="px-4 py-2">
                            @if ($user->active)
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="text-blue-500 hover:underline">Edit</a>

                                @if ($user->active)
                                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            data-confirm="Are you sure you want to suspend this user? They will not be able to access their account."
                                            data-confirm-title="Suspend User" data-confirm-type="suspend"
                                            data-confirm-text="Suspend"
                                            class="text-orange-500 hover:underline">Suspend</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            data-confirm="Are you sure you want to activate this user? They will regain access to their account."
                                            data-confirm-title="Activate User" data-confirm-type="activate"
                                            data-confirm-text="Activate"
                                            class="text-green-500 hover:underline">Activate</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
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
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                    <button id="modalConfirm" type="button"
                        class="px-4 py-2 text-white rounded-lg transition-colors">Confirm</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
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

                    const type = this.dataset.confirmType || 'warning';
                    let confirmClass = 'bg-blue-600 hover:bg-blue-700';
                    let iconHtml = '';

                    if (type === 'danger' || type === 'delete') {
                        confirmClass = 'bg-red-600 hover:bg-red-700';
                        iconHtml = '<svg class="w-6 h-6 text-red-600" ...></svg>';
                    } else if (type === 'suspend') {
                        confirmClass = 'bg-orange-600 hover:bg-orange-700';
                        iconHtml = '<svg class="w-6 h-6 text-orange-600" ...></svg>';
                    } else if (type === 'activate') {
                        confirmClass = 'bg-green-600 hover:bg-green-700';
                        iconHtml = '<svg class="w-6 h-6 text-green-600" ...></svg>';
                    } else {
                        iconHtml = '<svg class="w-6 h-6 text-blue-600" ...></svg>';
                    }

                    modalIcon.innerHTML = iconHtml;
                    modalConfirm.className = 'px-4 py-2 text-white rounded-lg transition-colors ' +
                        confirmClass;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            modalConfirm.addEventListener('click', function() {
                if (activeForm) {
                    activeForm.submit(); // ✅ normal submit preserves CSRF
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
@endsection
