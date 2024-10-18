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
            'teclado_id' => 1111,
            'role_id' => Role::where('name', 'Admin')->first()->id,

        ]);
        User::factory()->create([
            'name' => 'Pablo',
            'email' => 'iracustica@iracustica.com',
            'teclado_id' => 4321,
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Victor',
            'email' => 'pablo@iracustica.com',
            'teclado_id' => 1234,
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Alvaro',
            'email' => 'produccion@iracustica.com',
            'teclado_id' => 2345,
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Taller',
            'email' => 'taller@iracustica.com',
            'teclado_id' => 1122,
            'role_id' => Role::where('name', 'Taller')->first()->id,
        ]);

        $this->call(BrandsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ProductsSeeder::class);
    }
}
