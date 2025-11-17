<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - JobHunter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    @include('partials.header')

    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">My Messages</h1>
                <p class="text-gray-600 mt-1">Updates from your job applications</p>
            </div>
            @if ($unreadCount > 0)
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full">
                    <span class="font-semibold">{{ $unreadCount }}</span> unread
                    message{{ $unreadCount > 1 ? 's' : '' }}
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($messages->count() > 0)
            <div class="space-y-4">
                @foreach ($messages as $applicationId => $messageGroup)
                    @php
                        $latestMessage = $messageGroup->first();
                        $application = $latestMessage->application;
                        $applicationUnreadCount = $messageGroup->where('is_read', false)->count();
                        $hasUnread = $applicationUnreadCount > 0;
                    @endphp

                    <a href="{{ route('messages.show', $applicationId) }}"
                        class="block bg-white rounded-lg shadow p-5 hover:shadow-lg transition-all {{ $hasUnread ? 'border-l-4 border-blue-500' : '' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg text-gray-900 mb-1">
                                            {{ $application->job->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $application->job->company }}</p>
                                    </div>
                                    @if ($hasUnread)
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 whitespace-nowrap">
                                            {{ $applicationUnreadCount }} new
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 mb-3 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-600">Status:</span>
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if ($application->status === 'accepted') bg-green-100 text-green-800
                                            @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                            @elseif($application->status === 'interview_scheduled') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                    @if ($latestMessage->type)
                                        <span class="text-xs text-gray-500">
                                            @if ($latestMessage->type === 'interview')
                                                📅 Interview Update
                                            @elseif($latestMessage->type === 'acceptance')
                                                ✅ Acceptance
                                            @elseif($latestMessage->type === 'rejection')
                                                ❌ Update
                                            @else
                                                📧 Message
                                            @endif
                                        </span>
                                    @endif
                                </div>

                                <div class="bg-gray-50 rounded p-3 mb-2">
                                    <p class="text-sm text-gray-700 line-clamp-2">
                                        {{ Str::limit($latestMessage->content, 150) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500">
                                        {{ $latestMessage->created_at->diffForHumans() }}
                                    </p>
                                    @if ($hasUnread)
                                        <span class="text-xs font-medium text-blue-600">Click to view →</span>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white p-12 rounded-lg shadow text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No messages yet</h3>
                <p class="text-gray-600 mb-4">You'll see updates here when employers respond to your job applications.
                </p>
                <a href="{{ route('jobs.list') }}"
                    class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Browse Jobs
                </a>
            </div>
        @endif
    </div>

    @include('partials.confirm-modal')
</body>

</html>
