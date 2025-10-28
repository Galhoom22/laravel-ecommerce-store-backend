<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Create customer user
        $customer = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
        ]);
        $customer->assignRole('customer');

        // Create 8 random customers
        User::factory()->count(8)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
