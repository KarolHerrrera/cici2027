<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cici.test'],
            [
                'name'     => 'Administrador CICI',
                'password' => Hash::make('Cici2027*'),
                'is_admin' => true,
            ]
        );
    }
}