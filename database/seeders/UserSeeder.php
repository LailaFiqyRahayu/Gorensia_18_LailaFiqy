<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // pemisahan role. untuk mengisi tabel secara otomatis
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'phone' => '08123456789',
            'address' => 'Jalanain aja dulu stress nya belakangan',
            'identity_number' => '123456421'
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
            'phone' => '08123456789',
            'address' => 'Jalanain aja dulu stress nya belakangan',
            'identity_number' => '123456421'
        ]);
    }
}
