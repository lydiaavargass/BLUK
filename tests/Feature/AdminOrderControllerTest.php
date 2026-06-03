<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Sembrar roles, categorías y productos por defecto
        $this->artisan('db:seed');
    }

    public function test_guest_cannot_access_admin_orders_index(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin_orders_index(): void
    {
        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.orders.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_orders_index(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders');
    }

    public function test_admin_can_access_admin_orders_show(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pendiente',
            'total' => 50.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.show');
        $response->assertViewHas('order');
    }

    public function test_admin_can_update_order_status(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pendiente',
            'total' => 50.00,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'status' => 'procesando',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'procesando',
        ]);
    }

    public function test_admin_cancelling_order_restores_product_stock(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $product = Product::first();
        $initialStock = $product->stock; // ej. 10

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pendiente',
            'total' => $product->price * 2,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => 2,
        ]);

        // Cambiar estado a cancelado
        $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'status' => 'cancelado',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelado',
        ]);

        // Verificar que el stock aumentó en 2
        $product->refresh();
        $this->assertEquals($initialStock + 2, $product->stock);
    }

    public function test_admin_cancelling_already_cancelled_order_does_not_restore_stock_again(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $product = Product::first();
        $initialStock = $product->stock;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'cancelado', // Ya cancelado por defecto
            'total' => $product->price * 2,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => 2,
        ]);

        // Enviar patch a cancelado nuevamente
        $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
            'status' => 'cancelado',
        ]);

        // Debe retornar info o success pero no debe restaurar de nuevo
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelado',
        ]);

        $product->refresh();
        $this->assertEquals($initialStock, $product->stock); // Sigue igual
    }
}
