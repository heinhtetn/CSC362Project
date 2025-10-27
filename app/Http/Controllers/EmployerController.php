<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::with('user')->paginate(10);
        return view('employers.index', compact('employers'));
    }

    public function create()
    {
        $users = User::where('role', 'employer')->get();
        return view('employers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string',
            'industry' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        Employer::create($validated);
        return redirect()->route('employers.index')->with('success', 'Employer created.');
    }

    public function edit(Employer $employer)
    {
        $users = User::where('role', 'employer')->get();
        return view('employers.edit', compact('employer', 'users'));
    }

    public function update(Request $request, Employer $employer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string',
            'industry' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        $employer->update($validated);
        return redirect()->route('employers.index')->with('success', 'Updated.');
    }

    public function destroy(Employer $employer)
    {
        $employer->delete();
        return redirect()->route('employers.index')->with('success', 'Deleted.');
    }
}
