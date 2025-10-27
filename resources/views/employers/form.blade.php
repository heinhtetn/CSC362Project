@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Employer Form</h2>

    <form method="POST" action="{{ isset($employer) ? route('employers.update', $employer) : route('employers.store') }}">
        @csrf
        @if (isset($employer))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="block mb-1">Company Name</label>
            <input type="text" name="company_name" value="{{ old('company_name', $employer->company_name ?? '') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $employer->contact_person ?? '') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $employer->phone ?? '') }}"
                class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Address</label>
            <textarea name="address" class="w-full border rounded p-2">{{ old('address', $employer->address ?? '') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ isset($employer) ? 'Update' : 'Create' }}
        </button>
    </form>
@endsection
