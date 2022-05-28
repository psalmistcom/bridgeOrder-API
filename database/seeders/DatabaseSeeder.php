<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            BankSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            ConfigurationSeeder::class
        ]);
    }
}
