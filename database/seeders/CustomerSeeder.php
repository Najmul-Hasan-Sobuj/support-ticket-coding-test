<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create customer role if it doesn't exist
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // Create a customer user
        $customer = User::factory()->create([
            'name' => 'customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('customer123'),
        ]);

        // Assign the customer role
        $customer->assignRole($customerRole);
    }
}
