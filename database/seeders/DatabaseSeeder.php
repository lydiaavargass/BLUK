<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Datos base (orden importa por las FK)
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // 2. Usuario administrador
        $adminRole = Role::where('name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@bluk.test'],
            [
                'name' => 'Admin BLÜK',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // 3. Usuario cliente de prueba
        $clienteRole = Role::where('name', 'cliente')->first();

        User::firstOrCreate(
            ['email' => 'cliente@bluk.test'],
            [
                'name' => 'Cliente Test',
                'password' => bcrypt('password'),
                'role_id' => $clienteRole->id,
            ]
        );
    }
}

