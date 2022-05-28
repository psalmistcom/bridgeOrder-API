<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::updateOrCreate([
            'full_name' => 'Fred Dunphy',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => Role::whereSlug(Admin::ROLE_SUPER_ADMIN)->first()->id
        ]);
    }
}
