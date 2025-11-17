<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $applications = Application::with(['user.jobSeeker', 'job', 'interview', 'messages'])
            ->whereHas('job', fn($q) => $q->where('admin_id', $admin->id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $admin = Auth::guard('admin')->user();

        if ($application->job->admin_id !== $admin->id) {
            abort(403, 'Unauthorized access');
        }

        $application->load(['user.jobSeeker', 'job', 'interview', 'messages']);

        $resume = optional($application->user->jobSeeker)->resume;

        return view('admin.applications.show', compact('application', 'resume'));
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        $admin = Auth::guard('admin')->user();
        if ($application->job->admin_id !== $admin->id) abort(403);

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $interview = Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]
        );

        $application->update(['status' => 'interview_scheduled']);

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => "You have been scheduled for an interview for {$application->job->title}. " .
                "Date: " . date('F j, Y \a\t g:i A', strtotime($request->scheduled_at)) .
                ($request->location ? ". Location: {$request->location}" : "") .
                ($request->notes ? ". Notes: {$request->notes}" : ""),
            'type' => 'interview',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Interview scheduled successfully!');
    }

    public function decline(Request $request, Application $application)
    {
        $admin = Auth::guard('admin')->user();
        if ($application->job->admin_id !== $admin->id) abort(403);

        $request->validate(['reason' => 'nullable|string|max:500']);

        $application->update(['status' => 'rejected']);

        $messageContent = "We regret to inform you that your application for {$application->job->title} has been declined.";
        if ($request->reason) $messageContent .= " Reason: {$request->reason}";

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => $messageContent,
            'type' => 'rejection',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application declined and user notified.');
    }

    public function accept(Application $application)
    {
        $admin = Auth::guard('admin')->user();
        if ($application->job->admin_id !== $admin->id) abort(403);

        $application->update(['status' => 'accepted']);

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => "Congratulations! Your application for {$application->job->title} has been accepted.",
            'type' => 'acceptance',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application accepted and user notified.');
    }
}
