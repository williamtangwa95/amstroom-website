<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@amstroom.com'],
            [
                'name' => 'Amstroom Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Featured products
        $products = [
            [
                'name' => 'Dell Latitude 3310',
                'badge' => 'BEST SELLER',
                'description' => "Intel Core i3 8th Gen\n8GB RAM\n256GB SSD\n13.3\" HD Display",
                'price' => 650000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Lenovo Yoga 11e',
                'badge' => 'POPULAR',
                'description' => "Intel Celeron\n8GB RAM\n128GB SSD\nTouchscreen Display",
                'price' => 380000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Dell Latitude 3190 2-in-1',
                'badge' => 'NEW',
                'description' => "Pentium Silver\n8GB RAM\n180GB SSD\nTouchscreen",
                'price' => 450000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Wireless Mouse',
                'badge' => 'ACCESSORIES',
                'description' => "USB Receiver\nSilent Click\nLong Battery Life\nPlug & Play",
                'price' => 20000,
                'is_from_price' => true,
                'image_url' => 'https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'HP ProDesk Desktop',
                'badge' => 'OFFICE',
                'description' => "Intel Core i5\n8GB RAM\n500GB HDD\nWindows 11",
                'price' => 750000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => '24" Full HD Monitor',
                'badge' => 'NEW STOCK',
                'description' => "IPS Panel\nHDMI + VGA\n75Hz Refresh Rate\nSlim Bezel",
                'price' => 320000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'SSD Upgrade',
                'badge' => 'STORAGE',
                'description' => "256GB / 512GB / 1TB\nHigh Speed SSD\nLaptop & Desktop Compatible",
                'price' => 85000,
                'is_from_price' => true,
                'image_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Gaming Keyboard & Mouse Combo',
                'badge' => 'GAMING',
                'description' => "RGB Backlight\nUSB Connection\nMechanical Feel\nGaming Mouse Included",
                'price' => 75000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
