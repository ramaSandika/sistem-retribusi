<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator Retribusi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'unit_opd' => 'Dinas Pendapatan Utama',
        ]);

        User::create([
            'name' => 'Operator Dinas Parkir',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'unit_opd' => 'Dinas Perhubungan',
        ]);
    }
}