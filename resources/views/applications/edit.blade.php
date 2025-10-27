@extends('layouts.app')

@section('content')
    <h1>Edit Application</h1>

    <form action="{{ route('applications.update', $application) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Status:</label>
        <input type="text" name="status" value="{{ $application->status }}">
        <label>Cover Letter:</label>
        <textarea name="cover_letter">{{ $application->cover_letter }}</textarea>
        <button type="submit">Update</button>
    </form>
@endsection
