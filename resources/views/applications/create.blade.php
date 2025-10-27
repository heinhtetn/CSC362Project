@extends('layouts.app')

@section('content')
    <h1>Create Application</h1>

    <form action="{{ route('applications.store') }}" method="POST">
        @csrf
        <label>User ID:</label>
        <input type="number" name="user_id" required>
        <label>Job ID:</label>
        <input type="number" name="job_id" required>
        <label>Status:</label>
        <input type="text" name="status" value="pending">
        <label>Cover Letter:</label>
        <textarea name="cover_letter"></textarea>
        <button type="submit">Submit</button>
    </form>
@endsection
