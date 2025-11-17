<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    // Show saved jobs list
    public function index()
    {
        $savedJobs = Auth::user()
            ->jobSeeker
            ->savedJobs()
            ->latest()
            ->get();

        return view('saved-jobs', compact('savedJobs'));
    }

    // Save a job
    public function store($jobId)
    {
        $jobSeeker = Auth::user()->jobSeeker;

        // Prevent duplicates
        if (!$jobSeeker->savedJobs()->where('job_id', $jobId)->exists()) {
            $jobSeeker->savedJobs()->attach($jobId);
        }

        return back()->with('success', 'Job saved!');
    }

    // Remove a saved job
    public function destroy($jobId)
    {
        $jobSeeker = Auth::user()->jobSeeker;

        $jobSeeker->savedJobs()->detach($jobId);

        return back()->with('success', 'Removed from saved jobs');
    }
}
