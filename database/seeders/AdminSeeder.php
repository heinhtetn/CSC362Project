<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password will be automatically hashed by Admin model's setPasswordAttribute
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@jobhunter.com',
            'password' => 'password', // Model will hash this automatically
            'active' => true,
        ]);

        // You can add more admin accounts here if needed
        // Admin::create([
        //     'name' => 'Second Admin',
        //     'email' => 'admin2@jobhunter.com',
        //     'password' => 'password', // Model will hash this automatically
        //     'active' => true,
        // ]);
    }
}
