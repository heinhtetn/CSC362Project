<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureJobSeekerProfileExists
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && !$user->jobSeeker) {

            // Remember the previous page (the job they were applying for)
            session(['intended_url' => url()->previous()]);

            return redirect()->route('jobseeker.create')
                ->with('warning', 'Please complete your profile to continue.');
        }

        return $next($request);
    }
}
