<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Details - JobHunter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    @include('partials.header')

    <div class="max-w-4xl mx-auto p-6">
        <div class="mb-4">
            <a href="{{ route('messages') }}" class="text-blue-600 hover:underline">← Back to Messages</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-2xl font-bold mb-2">{{ $application->job->title }}</h2>
                <p class="text-gray-600">{{ $application->job->company }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $application->job->location }}</p>
            </div>
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Application Status:</span>
                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if ($application->status === 'accepted') bg-green-100 text-green-800
                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                        @elseif($application->status === 'interview_scheduled') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>
                @if ($application->interview)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="font-medium">Interview:</span>
                        <span>{{ $application->interview->scheduled_at->format('F j, Y g:i A') }}</span>
                        @if ($application->interview->location)
                            <span class="text-gray-500">at {{ $application->interview->location }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-6">Messages</h3>

            <div class="space-y-4">
                @foreach ($messages as $message)
                    <div
                        class="border-l-4 rounded-r-lg
                        @if ($message->type === 'interview') border-blue-500 bg-blue-50
                        @elseif($message->type === 'rejection') border-red-500 bg-red-50
                        @elseif($message->type === 'acceptance') border-green-500 bg-green-50
                        @else border-gray-500 bg-gray-50 @endif pl-5 py-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-gray-900">
                                        @if ($message->type === 'interview')
                                            📅 Interview Scheduled
                                        @elseif($message->type === 'rejection')
                                            ❌ Application Declined
                                        @elseif($message->type === 'acceptance')
                                            ✅ Application Accepted
                                        @else
                                            📧 Application Update
                                        @endif
                                    </p>
                                    @if (!$message->is_read)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ $message->created_at->format('F j, Y g:i A') }}
                                    ({{ $message->created_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded p-4 border border-gray-200">
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.confirm-modal')
</body>

</html>
