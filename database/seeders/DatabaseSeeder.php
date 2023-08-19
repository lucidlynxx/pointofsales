<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $kasirRole = Role::create(['name' => 'kasir']);

        $adminUser = \App\Models\User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('password123')
        ]);

        $kasirUser = \App\Models\User::create([
            'name' => 'Kasir',
            'username' => 'kasir',
            'email' => 'kasir@email.com',
            'password' => bcrypt('password123')
        ]);

        $adminUser->assignRole($adminRole);
        $kasirUser->assignRole($kasirRole);

        $this->call(UnitSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PermissionSeeder::class);
    }
}
