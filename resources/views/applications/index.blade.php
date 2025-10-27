@extends('layouts.app')

@section('content')
    <h1>Applications</h1>
    <a href="{{ route('applications.create') }}">Create New Application</a>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Job</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applications as $app)
                <tr>
                    <td>{{ $app->id }}</td>
                    <td>{{ $app->user->name }}</td>
                    <td>{{ $app->job->title }}</td>
                    <td>{{ $app->status }}</td>
                    <td>
                        <a href="{{ route('applications.edit', $app) }}">Edit</a>
                        <form action="{{ route('applications.destroy', $app) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this application?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $applications->links() }}
@endsection
