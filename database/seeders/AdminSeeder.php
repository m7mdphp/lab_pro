<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Only create if no admin exists yet
        if (User::where('is_admin', true)->exists()) {
            $this->command->info('Admin user already exists — skipping.');
            return;
        }

        $email    = env('ADMIN_EMAIL', 'admin@sheikhalab.com');
        $password = env('ADMIN_PASSWORD', 'Admin@1234!');

        User::create([
            'name'     => 'Admin',
            'email'    => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->command->info("Admin created: {$email}");
    }
}
