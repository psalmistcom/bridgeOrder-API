<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'slug' => 'super_admin',
            'name' => 'Super Admin',
            'type' => Role::ADMIN_TYPE
        ]);

        Role::updateOrCreate([
            'slug' => 'vendor_admin',
            'name' => 'Vendor Admin',
            'type' => Role::VENDOR_TYPE
        ]);

        Role::updateOrCreate([
            'slug' => 'vendor_service',
            'name' => 'Vendor Service',
            'type' => Role::VENDOR_TYPE
        ]);

        Role::updateOrCreate([
            'slug' => 'vendor_accounting',
            'name' => 'Vendor Accounting',
            'type' => Role::VENDOR_TYPE
        ]);
    }
}
