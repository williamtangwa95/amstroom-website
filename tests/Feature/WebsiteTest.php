<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the homepage loads successfully and shows seeded products.
     */
    public function test_homepage_loads_and_displays_products(): void
    {
        // Seed a product
        $product = Product::create([
            'name' => 'Dell Latitude Test',
            'badge' => 'BEST SELLER',
            'description' => 'Test specifications',
            'price' => 650000,
            'is_from_price' => false,
            'image_url' => 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dell Latitude Test');
        $response->assertSee('TZS 650,000');
    }

    /**
     * Test the contact form submission saves data and redirects.
     */
    public function test_contact_form_saves_data_and_redirects(): void
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+255 700 000 000',
            'message' => 'Hello, I want to inquire about a desktop computer.',
        ];

        $response = $this->post('/contact', $contactData);

        // Redirects back to homepage
        $response->assertRedirect(route('home'));
        
        // Assert message stored in database
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe',
            'phone' => '+255 700 000 000',
            'message' => 'Hello, I want to inquire about a desktop computer.',
        ]);
    }

    /**
     * Test admin login page loads successfully.
     */
    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('admin@amstroom.com');
    }

    /**
     * Test admin dashboard requires authentication.
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login'));
    }

    /**
     * Test admin can log in and view dashboard.
     */
    public function test_admin_can_login_and_view_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@amstroom.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@amstroom.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Overview');
    }
}
