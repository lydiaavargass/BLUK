<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Crea los dos roles del sistema: admin y cliente.
     */
    public function run(): void
    {
        $roles = ['admin', 'cliente'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
