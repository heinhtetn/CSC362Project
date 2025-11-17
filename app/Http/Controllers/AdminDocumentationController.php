<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        if (!$request->session()->get('documentation_authenticated')) {
            return view('admin.documentation-auth');
        }

        return view('admin.documentation');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        $request->session()->put('documentation_authenticated', true);

        return redirect()->route('admin.documentation')->with('success', 'Documentation unlocked.');
    }

    public function lock(Request $request)
    {
        $request->session()->forget('documentation_authenticated');

        return redirect()->route('admin.documentation')->with('status', 'Documentation locked.');
    }
}

