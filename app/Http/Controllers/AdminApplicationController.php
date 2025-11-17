<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\Interview;
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
        
        // Get applications for jobs created by this admin
        $applications = Application::with(['user', 'job', 'interview', 'messages'])
            ->whereHas('job', function ($query) use ($admin) {
                $query->where('admin_id', $admin->id);
            })
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin owns this job
        if ($application->job->admin_id !== $admin->id) {
            abort(403, 'Unauthorized access');
        }

        $application->load(['user', 'job', 'interview', 'messages']);

        return view('admin.applications.show', compact('application'));
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin owns this job
        if ($application->job->admin_id !== $admin->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Create interview
        $interview = Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]
        );

        // Update application status
        $application->update(['status' => 'interview_scheduled']);

        // Create message for user
        Message::create([
            'sender_id' => $admin->id, // Admin sends message
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => "You have been scheduled for an interview for the position: {$application->job->title}. " .
                        "Date: " . date('F j, Y \a\t g:i A', strtotime($request->scheduled_at)) .
                        ($request->location ? ". Location: {$request->location}" : "") .
                        ($request->notes ? ". Notes: {$request->notes}" : ""),
            'type' => 'interview',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.index')
            ->with('success', 'Interview scheduled successfully!');
    }

    public function decline(Request $request, Application $application)
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin owns this job
        if ($application->job->admin_id !== $admin->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Update application status
        $application->update(['status' => 'rejected']);

        // Create message for user
        $messageContent = "We regret to inform you that your application for the position: {$application->job->title} has been declined.";
        if ($request->reason) {
            $messageContent .= " Reason: {$request->reason}";
        }

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => $messageContent,
            'type' => 'rejection',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application declined and user notified.');
    }

    public function accept(Application $application)
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin owns this job
        if ($application->job->admin_id !== $admin->id) {
            abort(403, 'Unauthorized access');
        }

        // Update application status
        $application->update(['status' => 'accepted']);

        // Create message for user
        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $application->user_id,
            'application_id' => $application->id,
            'content' => "Congratulations! Your application for the position: {$application->job->title} has been accepted. We will contact you soon with further details.",
            'type' => 'acceptance',
            'is_read' => false,
        ]);

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application accepted and user notified.');
    }
}
