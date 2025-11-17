<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jobs')->insert([
            [
                'admin_id' => 1,
                'title' => 'Frontend Developer',
                'description' => 'We are looking for a skilled frontend developer with experience in HTML, CSS, JavaScript, and modern frameworks.',
                'location' => 'Bangkok, Thailand',
                'salary' => 45000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => 1,
                'title' => 'Backend Developer',
                'description' => 'Work with Laravel and MySQL to build scalable backend systems for our job platform.',
                'location' => 'Remote',
                'salary' => 55000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => 1,
                'title' => 'UI/UX Designer',
                'description' => 'Design clean and modern interfaces for web and mobile applications.',
                'location' => 'Tokyo, Japan',
                'salary' => 60000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admin_id' => 1,
                'title' => 'Digital Marketing Specialist',
                'description' => 'Manage social media, SEO, and content marketing campaigns for our clients.',
                'location' => 'Singapore',
                'salary' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
