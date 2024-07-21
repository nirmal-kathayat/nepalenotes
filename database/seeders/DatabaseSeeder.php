<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public $adminPassword = '$2y$10$JcmAHe5eUZ2rS0jU1GWr/.xhwCnh2RU13qwjTPcqfmtZXjZxcryPO';
    public function run(): void
    {
        // admin seeder
        \DB::connection('mysql')->table('admins')->insert([
            [
                'id' => '1',
                'username' => 'superadmin',
                'password' => $this->adminPassword,
                'email' => 'superadmin@gmail.com',
                'name' => 'Super Admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
