<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['user', 'job'])->paginate(10);
        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        return view('applications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'status' => 'required|string',
            'cover_letter' => 'nullable|string',
        ]);

        Application::create($request->all());

        return redirect()->route('applications.index')->with('success', 'Application created successfully.');
    }

    public function edit(Application $application)
    {
        return view('applications.edit', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|string',
            'cover_letter' => 'nullable|string',
        ]);

        $application->update($request->only('status', 'cover_letter'));

        return redirect()->route('applications.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted successfully.');
    }
}
