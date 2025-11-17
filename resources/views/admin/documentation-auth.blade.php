@extends('layouts.app')

@section('title', 'Documentation Lock')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded shadow p-8">
        <h2 class="text-2xl font-bold mb-4">Unlock Documentation</h2>
        <p class="text-gray-600 mb-6">
            For security reasons, please confirm your password to view the project documentation.
        </p>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 p-4 bg-blue-50 text-blue-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.documentation.authenticate') }}" class="space-y-4">
            @csrf
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Admin Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Unlock Documentation
            </button>
        </form>
    </div>
@endsection
