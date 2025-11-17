<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        
        // Get all messages for this user, grouped by application
        $messages = Message::with(['application.job', 'sender'])
            ->where('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('application_id');

        // Get unread message count for badge
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('messages.index', compact('messages', 'unreadCount'));
    }

    public function show($applicationId)
    {
        $user = Auth::guard('web')->user();
        
        // Get all messages for this application
        $messages = Message::with(['application.job', 'sender'])
            ->where('receiver_id', $user->id)
            ->where('application_id', $applicationId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            abort(404, 'Messages not found');
        }

        // Mark messages as read
        Message::where('receiver_id', $user->id)
            ->where('application_id', $applicationId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $application = $messages->first()->application;

        return view('messages.show', compact('messages', 'application'));
    }
}
