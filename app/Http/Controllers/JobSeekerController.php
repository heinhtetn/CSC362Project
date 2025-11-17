<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobSeekerController extends Controller
{
    public function create()
    {
        return view('jobseekers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'resume'    => 'nullable|mimes:pdf|max:2048', // 2MB max
        ]);

        $data = $request->only(['full_name', 'phone']);

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        auth()->user()->jobSeeker()->create($data);

        $redirect = session('intended_url') ?? route('jobs.list');

        return redirect($redirect)->with('success', 'Profile created successfully!');
    }

    public function show()
    {
        $profile = auth()->user()->jobSeeker;

        return view('jobseekers.profile', compact('profile'));
    }


    public function edit()
    {
        $profile = auth()->user()->jobSeeker;

        return view('jobseekers.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'resume'    => 'nullable|mimes:pdf|max:2048', // PDF only
        ]);

        $profile = auth()->user()->jobSeeker;

        $data = $request->only(['full_name', 'phone']);

        // Replace old resume if new one uploaded
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
