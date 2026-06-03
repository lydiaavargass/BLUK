<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Sembrar roles necesarios
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    public function test_guest_cannot_access_admin_users_index(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin_users_index(): void
    {
        $clienteRole = Role::where('name', 'cliente')->first();
        $user = User::factory()->create([
            'role_id' => $clienteRole->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_users_index(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }
}
