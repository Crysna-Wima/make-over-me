<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create mua
        for($i = 1; $i <= 10; $i++) {
            User::create([
                'email' => 'mua' . $i . '@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 3,
            ]);

        }

        // create client
        for($i = 1; $i <= 10; $i++) {
            User::create([
                'email' => 'client' . $i . '@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2,
            ]);
        }

        // command success message
        $this->command->info('User created successfully');
    }
}
