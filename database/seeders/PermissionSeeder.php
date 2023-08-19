<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'name' => 'View Products'
        ]);
        Permission::create([
            'name' => 'View Product'
        ]);
        Permission::create([
            'name' => 'Create Product'
        ]);
        Permission::create([
            'name' => 'Update Product'
        ]);
        Permission::create([
            'name' => 'View Categories'
        ]);
        Permission::create([
            'name' => 'View Category'
        ]);
        Permission::create([
            'name' => 'Create Category'
        ]);
        Permission::create([
            'name' => 'Update Category'
        ]);
        Permission::create([
            'name' => 'View Units'
        ]);
        Permission::create([
            'name' => 'View Unit'
        ]);
        Permission::create([
            'name' => 'Create Unit'
        ]);
        Permission::create([
            'name' => 'Update Unit'
        ]);
        Permission::create([
            'name' => 'View Sales'
        ]);
        Permission::create([
            'name' => 'View Sale'
        ]);
        Permission::create([
            'name' => 'Create Sale'
        ]);
        Permission::create([
            'name' => 'Update Sale'
        ]);
    }
}
