<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function list()
    {
        $jobs = Job::all();
        return view('jobs', compact('jobs'));
    }
    public function show($id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::guard('web')->user();

        $alreadyApplied = false;
        $alreadySaved = false;

        if ($user) {
            $alreadyApplied = Application::where('user_id', $user->id)
                ->where('job_id', $job->id)
                ->exists();

            if ($user->jobSeeker) {
                $alreadySaved = SavedJob::where('job_id', $job->id)
                    ->where('job_seeker_id', $user->jobSeeker->id)
                    ->exists();
            }
        }

        return view('job-details', compact('job', 'alreadyApplied', 'alreadySaved'));
    }

    public function apply(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::guard('web')->user();

        // Fetch job seeker profile
        $jobSeeker = $user->jobSeeker;

        // Prevent double application
        $existing = Application::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existing) {
            return redirect()->route('jobs.show', $id)
                ->with('info', 'You have already applied for this job.');
        }

        Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'pending',
            'cover_letter' => $request->input('cover_letter'),
        ]);

        return redirect()->route('jobs.show', $id)
            ->with('success', 'Application submitted successfully!');
    }


    public function save(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::guard('web')->user();

        // Check if job seeker exists for this user
        $jobSeeker = \App\Models\JobSeeker::where('user_id', $user->id)->first();

        if (!$jobSeeker) {
            return redirect()->route('jobs.show', $id)
                ->with('error', 'Job seeker profile not found.');
        }

        // Check if already saved
        $existing = \App\Models\SavedJob::where('job_seeker_id', $jobSeeker->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existing) {
            return redirect()->route('jobs.show', $id)
                ->with('info', 'Job already saved.');
        }

        // Create saved job
        \App\Models\SavedJob::create([
            'job_seeker_id' => $jobSeeker->id,
            'job_id' => $job->id,
        ]);

        return redirect()->route('jobs.show', $id)
            ->with('success', 'Job saved successfully!');
    }


    // This method is no longer used - messages are handled by MessageController
    // Keeping for backward compatibility but it's not routed
}
