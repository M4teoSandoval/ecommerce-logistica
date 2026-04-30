<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ecommerce.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'administrador',
                'phone'    => '3001234567',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'name'     => 'Cliente Test',
                'password' => Hash::make('12345678'),
                'role'     => 'cliente',
                'phone'    => '3009876543',
            ]
        );

        User::firstOrCreate(
            ['email' => 'proveedor@test.com'],
            [
                'name'     => 'Proveedor Test',
                'password' => Hash::make('12345678'),
                'role'     => 'proveedor',
                'phone'    => '3005551234',
            ]
        );

        User::firstOrCreate(
            ['email' => 'repartidor@test.com'],
            [
                'name'     => 'Repartidor Test',
                'password' => Hash::make('12345678'),
                'role'     => 'repartidor',
                'phone'    => '3004445678',
            ]
        );
    }
}
