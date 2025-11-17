<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function list()
    {
        $jobs = Job::with('admin')->paginate(12);
        return view('jobs', compact('jobs'));
    }
    public function show($id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::guard('web')->user();

        $alreadyApplied = false;
        $canReapply = false; // New flag
        $alreadySaved = false;

        if ($user) {
            $existingApplication = Application::where('user_id', $user->id)
                ->where('job_id', $job->id)
                ->first();

            if ($existingApplication) {
                if ($existingApplication->status === 'rejected') {
                    $canReapply = true; // allow re-apply
                } else {
                    $alreadyApplied = true;
                }
            }

            if ($user->jobSeeker) {
                $alreadySaved = SavedJob::where('job_id', $job->id)
                    ->where('job_seeker_id', $user->jobSeeker->id)
                    ->exists();
            }
        }

        return view('job-details', compact('job', 'alreadyApplied', 'canReapply', 'alreadySaved'));
    }


    public function apply(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = Auth::guard('web')->user();


        // Upload file
        if ($request->hasFile('cover_letter')) {
            // Validate cover letter file
            $request->validate([
                'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
            ]);
            $coverLetterFilePath = $request->file('cover_letter')->store('cover_letters', 'public');
        } else {
            $coverLetterFilePath = null;
        }

        // Check existing application
        $existing = Application::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existing && $existing->status !== 'rejected') {
            return redirect()->route('jobs.show', $id)
                ->with('info', 'You have already applied for this job.');
        }

        // Re-apply if rejected
        if ($existing && $existing->status === 'rejected') {

            // Remove old file if exists
            if ($existing->cover_letter_file) {
                Storage::disk('public')->delete($existing->cover_letter_file);
            }

            $existing->update([
                'status' => 'pending',
                'cover_letter_file' => $coverLetterFilePath,
                'updated_at' => now(),
            ]);

            return redirect()->route('jobs.show', $id)
                ->with('success', 'You have re-applied successfully!');
        }

        // Create new application
        Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'pending',
            'cover_letter' => $coverLetterFilePath,
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
