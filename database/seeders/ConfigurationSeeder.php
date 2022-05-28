<?php

namespace Database\Seeders;

use App\Models\Utility\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Configuration::updateOrCreate([
            'name' => 'vendor_commission_fee',
            'title' => 'Vendor Commission Fee',
            'default' => 0.1,
            'value' => 0.1,
            'value_type' => 'double',
            'editor_id' => 1,
        ]);
    }
}
