<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\VisitorLog;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoggingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test visitor logging middleware on public hits.
     */
    public function test_visitor_middleware_logs_page_views()
    {
        $this->assertEquals(0, VisitorLog::count());

        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals(1, VisitorLog::count());

        $log = VisitorLog::first();
        $this->assertEquals('127.0.0.1', $log->ip_address);
        $this->assertEquals('Localhost', $log->country);
        $this->assertEquals('GET', $log->method);
        $this->assertStringContainsString('http://localhost', $log->url);
    }

    /**
     * Test admin CRUD operations trigger activity logs.
     */
    public function test_admin_crud_operations_trigger_activity_logs()
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->assertEquals(0, ActivityLog::count());

        // Perform CRUD authenticated
        $this->actingAs($user);

        // 1. Create category
        $category = Category::create([
            'name' => 'Printers',
            'slug' => 'printers',
        ]);

        $this->assertEquals(1, ActivityLog::count());
        $log = ActivityLog::latest()->first();
        $this->assertEquals('created', $log->action);
        $this->assertEquals(Category::class, $log->model_type);
        $this->assertEquals($category->id, $log->model_id);
        $this->assertStringContainsString('Created a new Category: Printers', $log->details['message']);

        // 2. Update category name
        $category->update(['name' => 'Office Printers']);

        $this->assertEquals(2, ActivityLog::count());
        $log = ActivityLog::latest()->first();
        $this->assertEquals('updated', $log->action);
        $this->assertEquals('Printers', $log->details['before']['name']);
        $this->assertEquals('Office Printers', $log->details['after']['name']);

        // 3. Delete category
        $category->delete();

        $this->assertEquals(3, ActivityLog::count());
        $log = ActivityLog::latest()->first();
        $this->assertEquals('deleted', $log->action);
        $this->assertStringContainsString('Deleted Category: Office Printers', $log->details['message']);
    }

    /**
     * Test that analytics pages are secure and load properly for admin.
     */
    public function test_logs_pages_are_restricted_and_load_for_admin()
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);
        $guest = User::factory()->create([
            'role' => 'editor',
        ]);

        // Unauthenticated redirect to login
        $this->get(route('admin.logs.visitors'))->assertRedirect(route('login'));
        $this->get(route('admin.logs.activity'))->assertRedirect(route('login'));

        // Admin can access
        $this->actingAs($user);

        $response1 = $this->get(route('admin.logs.visitors'));
        $response1->assertStatus(200);
        $response1->assertSee('Visitor Analytics');

        $response2 = $this->get(route('admin.logs.activity'));
        $response2->assertStatus(200);
        $response2->assertSee('User Activity Logs');
    }
}
