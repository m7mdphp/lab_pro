<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            TestCategorySeeder::class,
            PackageSeeder::class,
            CompleteDataSeeder::class,
            BranchSeeder::class,
            FaqSeeder::class,
            PartnerSeeder::class,
        ]);
    }
}
