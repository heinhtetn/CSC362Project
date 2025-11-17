<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\JobSeekerController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| Public Guest Routes
|--------------------------------------------------------------------------
*/

// Landing + Job Listings
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/available-jobs', [FrontendController::class, 'list'])
    ->name('jobs.list');

Route::get('/jobs/{id}', [FrontendController::class, 'show'])
    ->name('jobs.show');

// Web Login/Register (Job Seekers)
Route::get('/login', function () {
    return view('auth.web.login');
})->name('web.login');

Route::post('/login', [AuthController::class, 'login'])->name('web.login.post');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('web.register');

Route::post('/register', [AuthController::class, 'register'])->name('web.register.post');


// Admin Login
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes - Job Seekers
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:web', 'job.profile'])->group(function () {
    // Job seeker features:
    Route::post('/jobs/{id}/apply', [FrontendController::class, 'apply'])->name('jobs.apply');
    Route::post('/jobs/{id}/save', [FrontendController::class, 'save'])->name('jobs.save');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{applicationId}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/jobseeker/saved-jobs', [SavedJobController::class, 'index'])->name('jobseeker.savedjobs');
    Route::delete('/jobseeker/saved-jobs/{jobId}/remove', [SavedJobController::class, 'destroy'])->name('jobseeker.unsavejob');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('web.logout')
    ->middleware('auth:web');

// Job Seeker Profile Completion
Route::middleware('auth:web')->group(function () {
    Route::get('/jobseeker/profile', [JobSeekerController::class, 'show'])->name('jobseeker.profile');
    Route::get('/jobseeker/profile/create', [JobSeekerController::class, 'create'])->name('jobseeker.create');
    Route::post('/jobseeker/profile/store', [JobSeekerController::class, 'store'])->name('jobseeker.store');
    Route::get('/jobseeker/profile/edit', [JobSeekerController::class, 'edit'])->name('jobseeker.edit');
    Route::put('/jobseeker/profile/update', [JobSeekerController::class, 'update'])->name('jobseeker.update');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Admin-only CRUD
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::resource('jobs', JobController::class);
    
    // Application Management
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/schedule-interview', [AdminApplicationController::class, 'scheduleInterview'])->name('applications.schedule-interview');
    Route::post('/applications/{application}/decline', [AdminApplicationController::class, 'decline'])->name('applications.decline');
    Route::post('/applications/{application}/accept', [AdminApplicationController::class, 'accept'])->name('applications.accept');
});


/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
// Route::fallback(function () {
//     return redirect()->route('landing');
// });
