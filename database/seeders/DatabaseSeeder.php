<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
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

        // Seed default system settings
        \App\Models\Setting::updateOrCreate(['key' => 'office_name'], ['value' => 'AMSTROOM COMPUTERS']);
        \App\Models\Setting::updateOrCreate(['key' => 'slogan'], ['value' => 'Technology Innovations • Fast & Reliable']);
        \App\Models\Setting::updateOrCreate(['key' => 'logo_path'], ['value' => 'images/logo.png']);
        \App\Models\Setting::updateOrCreate(['key' => 'contact_address'], ['value' => "Shop 101, 2H Plaza\nMorogoro, Tanzania"]);
        \App\Models\Setting::updateOrCreate(['key' => 'contact_phone'], ['value' => '+255 710 635 173']);
        \App\Models\Setting::updateOrCreate(['key' => 'contact_whatsapp'], ['value' => '+255 710 635 173']);
        \App\Models\Setting::updateOrCreate(['key' => 'contact_email'], ['value' => 'info@amstroomcomputers.com']);
        \App\Models\Setting::updateOrCreate(['key' => 'contact_hours'], ['value' => "Monday - Saturday\n8:00 AM – 7:00 PM"]);
        \App\Models\Setting::updateOrCreate(['key' => 'google_map_iframe'], ['value' => 'https://maps.google.com/maps?q=Shop%20101,%202H%20Plaza,%20Morogoro,%20Tanzania&t=&z=15&ie=UTF8&iwloc=&output=embed']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_instagram'], ['value' => 'https://instagram.com/amstroom_computers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_instagram_handle'], ['value' => '@amstroom_computers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_facebook'], ['value' => 'https://facebook.com/AmstroomComputers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_facebook_handle'], ['value' => 'Amstroom Computers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_tiktok'], ['value' => 'https://tiktok.com/@amstroom_computers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_tiktok_handle'], ['value' => 'Amstroom Computers']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_twitter'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_twitter_handle'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_linkedin'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_linkedin_handle'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_youtube'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'social_youtube_handle'], ['value' => '']);
        \App\Models\Setting::updateOrCreate(['key' => 'slider_interval'], ['value' => '5']);

        // Seed default Hero slide
        if (\App\Models\Slider::count() === 0) {
            \App\Models\Slider::create([
                'title' => 'FAST & RELIABLE TECHNOLOGY SOLUTIONS',
                'description' => 'Your trusted destination for laptops, desktops, accessories, software installation, repairs and professional IT services.',
                'primary_btn_text' => 'Browse Products',
                'primary_btn_url' => '#products',
                'secondary_btn_text' => 'WhatsApp Us',
                'secondary_btn_url' => 'https://wa.me/255710635173',
                'image_path' => null,
                'status' => true,
                'sort_order' => 0
            ]);
        }

        // Clear settings cache keys
        $cacheKeys = [
            'office_name', 'slogan', 'logo_path',
            'contact_address', 'contact_phone', 'contact_whatsapp', 'contact_email', 'contact_hours',
            'google_map_iframe',
            'social_instagram', 'social_instagram_handle',
            'social_facebook', 'social_facebook_handle',
            'social_tiktok', 'social_tiktok_handle',
            'social_twitter', 'social_twitter_handle',
            'social_linkedin', 'social_linkedin_handle',
            'social_youtube', 'social_youtube_handle',
            'slider_interval'
        ];
        foreach ($cacheKeys as $key) {
            \Illuminate\Support\Facades\Cache::forget("settings:{$key}");
        }

        // Seed product categories
        $this->call(CategorySeeder::class);

        // Fetch categories to link products
        $categories = Category::all()->keyBy('slug');

        // Featured products
        $products = [
            [
                'name' => 'Dell Latitude 3310',
                'badge' => 'BEST SELLER',
                'description' => "Intel Core i3 8th Gen\n8GB RAM\n256GB SSD\n13.3\" HD Display",
                'price' => 650000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['laptop']) ? $categories['laptop']->id : null,
            ],
            [
                'name' => 'Lenovo Yoga 11e',
                'badge' => 'POPULAR',
                'description' => "Intel Celeron\n8GB RAM\n128GB SSD\nTouchscreen Display",
                'price' => 380000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['laptop']) ? $categories['laptop']->id : null,
            ],
            [
                'name' => 'Dell Latitude 3190 2-in-1',
                'badge' => 'NEW',
                'description' => "Pentium Silver\n8GB RAM\n180GB SSD\nTouchscreen",
                'price' => 450000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['laptop']) ? $categories['laptop']->id : null,
            ],
            [
                'name' => 'Wireless Mouse',
                'badge' => 'ACCESSORIES',
                'description' => "USB Receiver\nSilent Click\nLong Battery Life\nPlug & Play",
                'price' => 20000,
                'is_from_price' => true,
                'image_url' => 'https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&w=800&q=80',
                'category_id' => null,
            ],
            [
                'name' => 'HP ProDesk Desktop',
                'badge' => 'OFFICE',
                'description' => "Intel Core i5\n8GB RAM\n500GB HDD\nWindows 11",
                'price' => 750000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['desktop']) ? $categories['desktop']->id : null,
            ],
            [
                'name' => '24" Full HD Monitor',
                'badge' => 'NEW STOCK',
                'description' => "IPS Panel\nHDMI + VGA\n75Hz Refresh Rate\nSlim Bezel",
                'price' => 320000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['monitor']) ? $categories['monitor']->id : null,
            ],
            [
                'name' => 'SSD Upgrade',
                'badge' => 'STORAGE',
                'description' => "256GB / 512GB / 1TB\nHigh Speed SSD\nLaptop & Desktop Compatible",
                'price' => 85000,
                'is_from_price' => true,
                'image_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['storage']) ? $categories['storage']->id : null,
            ],
            [
                'name' => 'Gaming Keyboard & Mouse Combo',
                'badge' => 'GAMING',
                'description' => "RGB Backlight\nUSB Connection\nMechanical Feel\nGaming Mouse Included",
                'price' => 75000,
                'is_from_price' => false,
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80',
                'category_id' => isset($categories['gaming']) ? $categories['gaming']->id : null,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }

        // Clear old records to prevent duplicates
        \App\Models\Service::query()->delete();
        \App\Models\WhyChoose::query()->delete();
        \App\Models\Stat::query()->delete();

        // Seed default Services
        $services = [
            [
                'title' => 'Laptop Sales',
                'icon' => 'fas fa-laptop',
                'description' => 'High-quality new and refurbished laptops from Dell, HP, Lenovo, Acer, ASUS, Apple and more.',
                'sort_order' => 0
            ],
            [
                'title' => 'Desktop Computers',
                'icon' => 'fas fa-desktop',
                'description' => 'Office desktops, gaming PCs, all-in-one computers, and custom-built desktop solutions.',
                'sort_order' => 1
            ],
            [
                'title' => 'Windows & Program (Software) Installations',
                'icon' => 'fab fa-windows',
                'description' => "Windows installation, activation, driver setup, formatting, upgrades, and optimization.\nMicrosoft Office, Adobe products, antivirus, accounting software, AutoCAD and other essential applications",
                'sort_order' => 2
            ],
            [
                'title' => 'Computer Repair',
                'icon' => 'fas fa-tools',
                'description' => 'Hardware diagnostics, motherboard repair, screen replacement, keyboard repair, battery replacement, and maintenance.',
                'sort_order' => 3
            ],
            [
                'title' => 'Printers & Computer Accessories',
                'icon' => 'fas fa-print',
                'description' => 'Printers, cartridges, computer accessories, flash drives, SSDs, HDDs, keyboards, mice and more.',
                'sort_order' => 4
            ],
            [
                'title' => 'Security & Surverance',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Installation of CCTV camera, Electric Fence, Biometrics, Alarm Systems and other security solutions.',
                'sort_order' => 5
            ],
            [
                'title' => 'Data Backup & Recovery',
                'icon' => 'fas fa-database',
                'description' => 'Professional data backup services for individuals, businesses, schools, and organizations.',
                'sort_order' => 6
            ],
            [
                'title' => 'Gaming Acessories',
                'icon' => 'fas fa-gamepad',
                'description' => 'Professional Gaming Acessories services for gaming.',
                'sort_order' => 7
            ],
            [
                'title' => 'IT Support',
                'icon' => 'fas fa-headset',
                'description' => 'Professional IT support services for individuals, businesses, schools, and organizations.',
                'sort_order' => 8
            ]
        ];

        foreach ($services as $srv) {
            \App\Models\Service::updateOrCreate(
                ['title' => $srv['title']],
                $srv
            );
        }

        // Seed default Why Chooses
        $whyChooses = [
            [
                'title' => 'Quality Guaranteed',
                'icon' => 'fas fa-shield-halved',
                'description' => 'Every product is carefully tested and verified before delivery, ensuring reliability and excellent performance.',
                'sort_order' => 0
            ],
            [
                'title' => 'Affordable Prices',
                'icon' => 'fas fa-tags',
                'description' => 'Competitive prices on laptops, desktops, accessories, and IT services without compromising quality.',
                'sort_order' => 1
            ],
            [
                'title' => 'Professional Support',
                'icon' => 'fas fa-headset',
                'description' => 'Friendly and experienced technicians ready to help before and after every purchase.',
                'sort_order' => 2
            ],
            [
                'title' => 'Fast Delivery',
                'icon' => 'fas fa-truck-fast',
                'description' => 'We provide quick and secure delivery services to customers across Tanzania.',
                'sort_order' => 3
            ],
            [
                'title' => '30+ Days Warranty',
                'icon' => 'fas fa-award',
                'description' => 'Selected products include a warranty for your confidence and peace of mind.',
                'sort_order' => 4
            ],
            [
                'title' => 'Trusted by Customers',
                'icon' => 'fas fa-users',
                'description' => 'Hundreds of satisfied customers continue to choose AMSTROOM COMPUTERS for quality and dependable service.',
                'sort_order' => 5
            ]
        ];

        foreach ($whyChooses as $wc) {
            \App\Models\WhyChoose::updateOrCreate(
                ['title' => $wc['title']],
                $wc
            );
        }

        // Seed default Stats
        $stats = [
            [
                'value' => '500+',
                'label' => 'Happy Customers',
                'sort_order' => 0
            ],
            [
                'value' => '30+ Days',
                'label' => 'Warranty',
                'sort_order' => 1
            ],
            [
                'value' => '24/7',
                'label' => 'Customer Support',
                'sort_order' => 2
            ],
            [
                'value' => '100%',
                'label' => 'Quality Products',
                'sort_order' => 3
            ]
        ];

        foreach ($stats as $st) {
            \App\Models\Stat::updateOrCreate(
                ['label' => $st['label']],
                $st
            );
        }
    }
}
