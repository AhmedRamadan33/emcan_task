<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');


        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'editor',
                'email' => 'editor@example.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
        $editor->assignRole('editor');


        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');


        $this->command->info('Default users created: admin, editor, customer.');
    }
}
