<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminInterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Show the edit form
    public function edit(Application $application)
    {
        $admin = Auth::guard('admin')->user();
        if ($application->job->admin_id !== $admin->id) abort(403);

        $interview = $application->interview;

        if (!$interview) {
            return redirect()->route('admin.applications.show', $application)
                ->with('error', 'No interview scheduled yet to edit.');
        }

        return view('interviews.edit', compact('application', 'interview'));
    }

    // Update interview
    public function update(Request $request, Application $application)
    {
        $admin = Auth::guard('admin')->user();
        if ($application->job->admin_id !== $admin->id) abort(403);

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $interview = $application->interview;
        $interview->update([
            'scheduled_at' => $request->scheduled_at,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        // Notify user about updated schedule
        $messageContent = "Your interview schedule for {$application->job->title} has been updated. " .
            "Date: " . date('F j, Y \a\t g:i A', strtotime($request->scheduled_at)) .
            ($request->location ? ". Location: {$request->location}" : "") .
            ($request->notes ? ". Notes: {$request->notes}" : "");

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => $messageContent,
            'type' => 'interview_update',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Interview updated and user notified.');
    }
}
