<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@sheikhalab.com');

        if (DB::table('users')->where('email', $email)->doesntExist()) {
            DB::table('users')->insert([
                'name'              => 'Admin',
                'email'             => $email,
                'password'          => Hash::make(env('ADMIN_PASSWORD', 'Admin@1234!')),
                'is_admin'          => true,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('users')->where('email', env('ADMIN_EMAIL', 'admin@sheikhalab.com'))->delete();
    }
};
