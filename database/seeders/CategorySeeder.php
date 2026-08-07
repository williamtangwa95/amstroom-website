<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
            ],
            [
                'name' => 'Desktop',
                'slug' => 'desktop',
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
            ],
            [
                'name' => 'Printers',
                'slug' => 'printers',
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
            ],
            [
                'name' => 'Monitor',
                'slug' => 'monitor',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
