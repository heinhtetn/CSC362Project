<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\JobSeekerController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\AdminDocumentationController;
use App\Http\Controllers\AdminInterviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;

// =======================================================
// PUBLIC ROUTES
// =======================================================

// Landing
Route::view('/', 'welcome')->name('landing');

// Public job listing + job details
Route::get('/available-jobs', [FrontendController::class, 'list'])->name('jobs.list');
Route::get('/jobs/{id}', [FrontendController::class, 'show'])->name('jobs.show');


// =======================================================
// AUTH — JOB SEEKER (USER)
// =======================================================

// Show login only for guests
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.web.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('web.register');
    Route::post('/register', [AuthController::class, 'register'])->name('web.register.post');
});

// User logout MUST be outside middleware (big fix!)
Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');


// =======================================================
// AUTHENTICATED JOB SEEKER FEATURES
// =======================================================

Route::middleware(['auth:web', 'user.active'])->group(function () {

    // Only run job.profile AFTER login success
    Route::middleware('job.profile')->group(function () {

        Route::post('/jobs/{id}/apply', [FrontendController::class, 'apply'])->name('jobs.apply');
        Route::post('/jobs/{id}/save', [FrontendController::class, 'save'])->name('jobs.save');

        Route::get('/messages', [MessageController::class, 'index'])->name('messages');
        Route::get('/messages/{applicationId}', [MessageController::class, 'show'])->name('messages.show');

        Route::get('/jobseeker/saved-jobs', [SavedJobController::class, 'index'])->name('jobseeker.savedjobs');
        Route::delete(
            '/jobseeker/saved-jobs/{jobId}/remove',
            [SavedJobController::class, 'destroy']
        )->name('jobseeker.unsavejob');
    });
});


// =======================================================
// PROFILE CREATION (accessible BEFORE job.profile)
// =======================================================

Route::middleware(['auth:web', 'user.active'])->group(function () {
    Route::get('/jobseeker/profile', [JobSeekerController::class, 'show'])->name('jobseeker.profile');
    Route::get('/jobseeker/profile/create', [JobSeekerController::class, 'create'])->name('jobseeker.create');
    Route::post('/jobseeker/profile/store', [JobSeekerController::class, 'store'])->name('jobseeker.store');
    Route::get('/jobseeker/profile/edit', [JobSeekerController::class, 'edit'])->name('jobseeker.edit');
    Route::put('/jobseeker/profile/update', [JobSeekerController::class, 'update'])->name('jobseeker.update');
});


// =======================================================
// ADMIN AUTH
// =======================================================

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

// Admin logout must be open
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


// =======================================================
// ADMIN PANEL
// =======================================================

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');

    Route::resource('jobs', JobController::class);

    // Applications
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/schedule-interview', [AdminApplicationController::class, 'scheduleInterview'])->name('applications.schedule-interview');
    Route::post('/applications/{application}/decline', [AdminApplicationController::class, 'decline'])->name('applications.decline');
    Route::post('/applications/{application}/accept', [AdminApplicationController::class, 'accept'])->name('applications.accept');

    // Documentation
    Route::get('/documentation', [AdminDocumentationController::class, 'index'])->name('documentation');
    Route::post('/documentation/authenticate', [AdminDocumentationController::class, 'authenticate'])->name('documentation.authenticate');
    Route::post('/documentation/lock', [AdminDocumentationController::class, 'lock'])->name('documentation.lock');

    // Interview management
    Route::get('applications/{application}/interview/edit', [AdminInterviewController::class, 'edit'])->name('interviews.edit');
    Route::put('applications/{application}/interview', [AdminInterviewController::class, 'update'])->name('interviews.update');
});
