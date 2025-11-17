<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - JobHunter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Messages</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($messages->count() > 0)
            <div class="bg-white rounded shadow">
                <div class="divide-y divide-gray-200">
                    @foreach ($messages as $message)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold">
                                        From: {{ $message->sender->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        To: {{ $message->receiver->name ?? 'Unknown' }}
                                    </p>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $message->sent_at ? $message->sent_at->format('M d, Y H:i') : 'N/A' }}
                                </span>
                            </div>
                            <p class="text-gray-700">{{ $message->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white p-8 rounded shadow text-center">
                <p class="text-gray-600">No messages found.</p>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('jobs.list') }}" class="text-blue-600 hover:underline">← Back to Jobs</a>
        </div>
    </div>
</body>

</html>
