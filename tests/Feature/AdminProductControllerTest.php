<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles, categories, products
        $this->artisan('db:seed');
    }

    public function test_guest_cannot_access_admin_products_index(): void
    {
        $response = $this->get(route('admin.products.index'));

        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin_products_index(): void
    {
        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.products.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_products_index(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
    }

    public function test_admin_can_filter_products_by_search_query(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        // "Camiseta BLÜK Classic" exists from seed
        $response = $this->actingAs($admin)->get(route('admin.products.index', ['buscar' => 'Classic']));

        $response->assertStatus(200);
        $response->assertSee('Camiseta BLÜK Classic');
        $response->assertDontSee('Sneaker Platform'); // Seeder has "Sneaker Platform", should not be shown
    }
}
