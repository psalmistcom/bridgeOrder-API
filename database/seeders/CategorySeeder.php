<?php

namespace Database\Seeders;

use App\Models\Vendor\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::updateOrCreate([
            'slug' => 'mains',
            'name' => 'Mains'
        ]);

        Category::updateOrCreate([
            'slug' => 'proteins',
            'name' => 'Proteins'
        ]);

        Category::updateOrCreate([
            'slug' => 'drinks',
            'name' => 'Drinks'
        ]);

        Category::updateOrCreate([
            'slug' => 'pastries',
            'name' => 'Pastries'
        ]);

        Category::updateOrCreate([
            'slug' => 'sides',
            'name' => 'Sides'
        ]);

        Category::updateOrCreate([
            'slug' => 'continental',
            'name' => 'Continental'
        ]);

        Category::updateOrCreate([
            'slug' => 'alcohol',
            'name' => 'Alcohol'
        ]);

        Category::updateOrCreate([
            'slug' => 'others',
            'name' => 'Others'
        ]);
    }
}
