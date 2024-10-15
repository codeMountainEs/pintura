<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $roles = [
            'Admin',
            'Taller'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@cdm.com',
            'teclado_id' => 1234,
            'role_id' => Role::where('name', 'Admin')->first()->id,

        ]);
        User::factory()->create([
            'name' => 'Pablo',
            'email' => 'pablo@iracustica.com',
            'teclado_id' => 4321
        ]);

        $this->call(BrandsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ProductsSeeder::class);
    }
}
