<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_guest_can_access_cart_page(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cart.index');
        $response->assertViewHas('products');
    }

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = Product::first();

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(
            [ $product->id => ['quantity' => 2] ],
            session()->get('cart')
        );
    }

    public function test_guest_cannot_add_product_exceeding_stock(): void
    {
        $product = Product::first();
        $product->update(['stock' => 5]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 6,
        ]);

        $response->assertSessionHas('error');
        $this->assertNull(session()->get('cart'));
    }

    public function test_guest_can_update_product_quantity(): void
    {
        $product = Product::first();
        $product->update(['stock' => 10]);

        // Guardamos en sesión directamente
        session()->put('cart', [
            $product->id => ['quantity' => 2]
        ]);

        $response = $this->patch(route('cart.update', $product->id), [
            'quantity' => 5,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(
            [ $product->id => ['quantity' => 5] ],
            session()->get('cart')
        );
    }

    public function test_guest_can_remove_product(): void
    {
        $product = Product::first();
        session()->put('cart', [
            $product->id => ['quantity' => 2]
        ]);

        $response = $this->delete(route('cart.destroy', $product->id));

        $response->assertSessionHas('success');
        $this->assertEmpty(session()->get('cart'));
    }

    public function test_authenticated_user_can_add_product_to_cart(): void
    {
        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create(['role_id' => $clienteRole->id]);
        $product = Product::first();

        $response = $this->actingAs($user)->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('cart.index'));

        // Verificar inserción en base de datos
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_session_cart_is_merged_on_login(): void
    {
        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create(['role_id' => $clienteRole->id]);
        $product = Product::first();

        // 1. Agregar al carrito de sesión como invitado
        session()->put('cart', [
            $product->id => ['quantity' => 2]
        ]);

        // 2. Iniciar sesión
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        
        // 3. El carrito de sesión debe estar vacío ahora
        $this->assertNull(session()->get('cart'));

        // 4. El carrito de base de datos debe contener el producto fusionado
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
