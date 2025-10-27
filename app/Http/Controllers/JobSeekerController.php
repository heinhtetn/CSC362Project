<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;
use App\Models\User;
use Illuminate\Http\Request;

class JobSeekerController extends Controller
{
    public function index()
    {
        $jobSeekers = JobSeeker::with('user')->paginate(10);
        return view('job_seekers.index', compact('jobSeekers'));
    }

    public function create()
    {
        $users = User::where('role', 'job_seeker')->get();
        return view('job_seekers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'resume' => 'nullable|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string'
        ]);

        JobSeeker::create($validated);
        return redirect()->route('job_seekers.index')->with('success', 'Job Seeker created.');
    }

    public function edit(JobSeeker $jobSeeker)
    {
        $users = User::where('role', 'job_seeker')->get();
        return view('job_seekers.edit', compact('jobSeeker', 'users'));
    }

    public function update(Request $request, JobSeeker $jobSeeker)
    {
        $validated = $request->validate([
            'resume' => 'nullable|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string'
        ]);

        $jobSeeker->update($validated);
        return redirect()->route('job_seekers.index')->with('success', 'Job Seeker updated.');
    }

    public function destroy(JobSeeker $jobSeeker)
    {
        $jobSeeker->delete();
        return redirect()->route('job_seekers.index')->with('success', 'Deleted.');
    }
}
