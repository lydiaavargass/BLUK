<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
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

    public function test_admin_can_access_create_product_form(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas('categories');
    }

    public function test_admin_can_store_a_new_product(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $category = Category::first();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Producto Test Nuevo',
            'description' => 'Una descripción de prueba para el producto.',
            'category_id' => $category->id,
            'price' => 25.50,
            'stock' => 10,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'name' => 'Producto Test Nuevo',
            'price' => 25.50,
        ]);
    }

    public function test_store_product_fails_with_missing_required_fields(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'description', 'category_id', 'price', 'stock']);
    }

    public function test_admin_can_access_edit_product_form(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $product = Product::first();

        $response = $this->actingAs($admin)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('product');
        $response->assertViewHas('categories');
    }

    public function test_admin_can_update_a_product(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $product = Product::first();
        $category = Category::first();

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'name' => 'Producto Editado',
            'description' => 'Descripción editada para el test.',
            'category_id' => $category->id,
            'price' => 99.99,
            'stock' => 5,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Producto Editado',
            'price' => 99.99,
        ]);
    }

    public function test_admin_can_deactivate_a_product(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $product = Product::where('is_active', true)->first();

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => false,
        ]);
    }
}
