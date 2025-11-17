@extends('layouts.app')

@section('title', 'Project Documentation')

@section('content')
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('status'))
        <div class="mb-4 p-4 bg-blue-50 text-blue-700 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <form method="POST" action="{{ route('admin.documentation.lock') }}">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors text-sm">
                Lock Documentation
            </button>
        </form>
    </div>

    <div class="space-y-8">
        <!-- Project Scope & Features -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Project Scope & Features</h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Project Overview</h3>
                    <p class="text-gray-700">
                        JobHunter is a comprehensive job portal platform that connects job seekers with employers.
                        The system facilitates job postings, applications, interviews, and communication between all
                        parties.
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Key Features</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li><strong>User Management:</strong> Separate authentication for job seekers and admins with
                            account suspension capabilities</li>
                        <li><strong>Job Posting:</strong> Admins can create, edit, and manage job listings with details like
                            title, description, location, and salary</li>
                        <li><strong>Job Search:</strong> Job seekers can browse available jobs with pagination support</li>
                        <li><strong>Application System:</strong> Job seekers can apply for jobs with cover letters and
                            resume uploads</li>
                        <li><strong>Saved Jobs:</strong> Job seekers can save jobs for later review</li>
                        <li><strong>Application Management:</strong> Admins can review, accept, reject, or schedule
                            interviews for applications</li>
                        <li><strong>Interview Scheduling:</strong> Admins can schedule interviews with applicants</li>
                        <li><strong>Messaging System:</strong> Communication between admins and job seekers regarding
                            applications</li>
                        <li><strong>Account Suspension:</strong> Admins can suspend/activate user accounts with instant
                            logout functionality</li>
                        <li><strong>Profile Management:</strong> Job seekers can create and manage their profiles</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">User Roles</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li><strong>Job Seeker:</strong> Can browse jobs, apply, save jobs, manage profile, and communicate
                            with admins</li>
                        <li><strong>Admin:</strong> Can manage users, post jobs, review applications, schedule interviews,
                            and communicate with job seekers</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ER Diagram -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Entity Relationship Diagram</h2>
            <div class="bg-gray-50 p-4 rounded border-2 border-gray-300 font-mono text-sm overflow-x-auto">
                <pre>
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│    Users    │─────────│  JobSeekers  │         │   Admins    │
├─────────────┤ 1:1     ├──────────────┤         ├─────────────┤
│ id (PK)     │         │ id (PK)      │         │ id (PK)     │
│ name        │         │ user_id (FK) │         │ name        │
│ email       │         │ full_name    │         │ email       │
│ password    │         │ phone        │         │ password    │
│ active      │         │ resume       │         │ active      │
│ timestamps  │         │ timestamps   │         │ timestamps  │
└─────────────┘         └──────────────┘         └─────────────┘
       │                        │                        │
       │                        │                        │
       │ 1:N                    │ N:M                    │ 1:N
       │                        │                        │
       ▼                        ▼                        ▼
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│Applications │         │  Saved Jobs  │         │    Jobs     │
├─────────────┤         ├──────────────┤         ├─────────────┤
│ id (PK)     │         │ id (PK)      │         │ id (PK)     │
│ user_id(FK) │         │ job_id (FK)  │         │ admin_id(FK)│
│ job_id (FK) │         │ job_seeker_id│         │ title       │
│ status      │         │ timestamps   │         │ description │
│ cover_letter│         └──────────────┘         │ location    │
│ resume_path │                                  │ salary      │
│ timestamps  │                                  │ timestamps  │
└─────────────┘                                  └─────────────┘
       │                                                │
       │ 1:1                                            │ 1:N
       │                                                │
       ▼                                                │
┌─────────────┐                                         │
│  Interviews │                                         │
├─────────────┤                                         │
│ id (PK)     │                                         │
│ application_│                                         │
│   id (FK)   │                                         │
│ scheduled_at│                                         │
│ location    │                                         │
│ notes       │                                         │
│ status      │                                         │
│ timestamps  │                                         │
└─────────────┘                                         │
                                                        │
                                                        │
                                                        ▼
                                               ┌─────────────┐
                                               │  Messages   │
                                               ├─────────────┤
                                               │ id (PK)     │
                                               │ sender_id(FK)│
                                               │ receiver_id │
                                               │ application_│
                                               │   id (FK)   │
                                               │ content     │
                                               │ type        │
                                               │ is_read     │
                                               │ timestamps  │
                                               └─────────────┘
                </pre>
            </div>
        </div>

        <!-- Relationships -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Database Relationships</h2>

            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Users → JobSeekers (One-to-One)</h3>
                    <p class="text-gray-700">Each user can have one job seeker profile. When a user registers, they can
                        create a job seeker profile with additional information.</p>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Users → Applications (One-to-Many)</h3>
                    <p class="text-gray-700">A user can submit multiple job applications. Each application belongs to one
                        user and one job.</p>
                </div>

                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Admins → Jobs (One-to-Many)</h3>
                    <p class="text-gray-700">An admin can create multiple job postings. Each job belongs to one admin.</p>
                </div>

                <div class="border-l-4 border-orange-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Jobs → Applications (One-to-Many)</h3>
                    <p class="text-gray-700">A job can receive multiple applications from different users. Each application
                        is for one specific job.</p>
                </div>

                <div class="border-l-4 border-red-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Applications → Interviews (One-to-One)</h3>
                    <p class="text-gray-700">Each application can have one scheduled interview. The interview contains
                        scheduling details and notes.</p>
                </div>

                <div class="border-l-4 border-indigo-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">JobSeekers ↔ Jobs (Many-to-Many via Saved Jobs)</h3>
                    <p class="text-gray-700">Job seekers can save multiple jobs, and jobs can be saved by multiple job
                        seekers. This relationship is managed through the saved_jobs pivot table.</p>
                </div>

                <div class="border-l-4 border-teal-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Applications → Messages (One-to-Many)</h3>
                    <p class="text-gray-700">An application can have multiple messages exchanged between the admin and the
                        job seeker regarding the application status.</p>
                </div>

                <div class="border-l-4 border-pink-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Admins → Messages (One-to-Many as Sender)</h3>
                    <p class="text-gray-700">Admins can send multiple messages to job seekers, typically related to
                        application updates, interview scheduling, or rejections.</p>
                </div>

                <div class="border-l-4 border-yellow-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Users → Messages (One-to-Many as Receiver)</h3>
                    <p class="text-gray-700">Users can receive multiple messages from admins regarding their applications
                        and job opportunities.</p>
                </div>
            </div>
        </div>

        <!-- Integrity Constraints -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Integrity Constraints</h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Primary Keys</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li>All tables have an <code class="bg-gray-100 px-1 rounded">id</code> column as the primary key
                            (auto-incrementing)</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Foreign Key Constraints</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li><code class="bg-gray-100 px-1 rounded">job_seekers.user_id</code> → <code
                                class="bg-gray-100 px-1 rounded">users.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">applications.user_id</code> → <code
                                class="bg-gray-100 px-1 rounded">users.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">applications.job_id</code> → <code
                                class="bg-gray-100 px-1 rounded">jobs.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">jobs.admin_id</code> → <code
                                class="bg-gray-100 px-1 rounded">admins.id</code> (SET NULL on delete)</li>
                        <li><code class="bg-gray-100 px-1 rounded">interviews.application_id</code> → <code
                                class="bg-gray-100 px-1 rounded">applications.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">saved_jobs.job_id</code> → <code
                                class="bg-gray-100 px-1 rounded">jobs.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">saved_jobs.job_seeker_id</code> → <code
                                class="bg-gray-100 px-1 rounded">job_seekers.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">messages.sender_id</code> → <code
                                class="bg-gray-100 px-1 rounded">admins.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">messages.receiver_id</code> → <code
                                class="bg-gray-100 px-1 rounded">users.id</code> (CASCADE DELETE)</li>
                        <li><code class="bg-gray-100 px-1 rounded">messages.application_id</code> → <code
                                class="bg-gray-100 px-1 rounded">applications.id</code> (CASCADE DELETE)</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Unique Constraints</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li><code class="bg-gray-100 px-1 rounded">users.email</code> - Must be unique</li>
                        <li><code class="bg-gray-100 px-1 rounded">admins.email</code> - Must be unique</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Default Values</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li><code class="bg-gray-100 px-1 rounded">users.active</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">true</code></li>
                        <li><code class="bg-gray-100 px-1 rounded">admins.active</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">true</code></li>
                        <li><code class="bg-gray-100 px-1 rounded">applications.status</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">'pending'</code></li>
                        <li><code class="bg-gray-100 px-1 rounded">interviews.status</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">'scheduled'</code></li>
                        <li><code class="bg-gray-100 px-1 rounded">messages.is_read</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">false</code></li>
                        <li><code class="bg-gray-100 px-1 rounded">messages.type</code> - Default: <code
                                class="bg-gray-100 px-1 rounded">'application'</code></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Nullable Fields</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li>Job fields: <code class="bg-gray-100 px-1 rounded">location</code>, <code
                                class="bg-gray-100 px-1 rounded">salary</code>, <code
                                class="bg-gray-100 px-1 rounded">admin_id</code></li>
                        <li>JobSeeker fields: <code class="bg-gray-100 px-1 rounded">phone</code>, <code
                                class="bg-gray-100 px-1 rounded">resume</code></li>
                        <li>Application fields: <code class="bg-gray-100 px-1 rounded">cover_letter</code>, <code
                                class="bg-gray-100 px-1 rounded">resume_path</code></li>
                        <li>Interview fields: <code class="bg-gray-100 px-1 rounded">location</code>, <code
                                class="bg-gray-100 px-1 rounded">notes</code></li>
                        <li>Message fields: <code class="bg-gray-100 px-1 rounded">sender_id</code>, <code
                                class="bg-gray-100 px-1 rounded">receiver_id</code>, <code
                                class="bg-gray-100 px-1 rounded">application_id</code></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-2">Business Logic Constraints</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        <li>Users cannot log in if <code class="bg-gray-100 px-1 rounded">active = false</code></li>
                        <li>Suspended users are automatically logged out on any request</li>
                        <li>Application status can be: <code class="bg-gray-100 px-1 rounded">pending</code>, <code
                                class="bg-gray-100 px-1 rounded">accepted</code>, <code
                                class="bg-gray-100 px-1 rounded">rejected</code></li>
                        <li>Message types include: <code class="bg-gray-100 px-1 rounded">application</code>, <code
                                class="bg-gray-100 px-1 rounded">interview</code>, <code
                                class="bg-gray-100 px-1 rounded">rejection</code>, <code
                                class="bg-gray-100 px-1 rounded">acceptance</code></li>
                        <li>Job seekers must complete their profile before applying to jobs</li>
                        <li>Each user can only apply once per job</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Technical Stack -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Technical Stack</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li><strong>Backend Framework:</strong> Laravel (PHP)</li>
                <li><strong>Database:</strong> SQLite (can be configured for MySQL/PostgreSQL)</li>
                <li><strong>Frontend:</strong> Blade Templates with Tailwind CSS</li>
                <li><strong>Authentication:</strong> Laravel Session-based authentication with multiple guards (web, admin)
                </li>
                <li><strong>Middleware:</strong> Custom middleware for profile completion and account status checks</li>
            </ul>
        </div>
    </div>
@endsection
