<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@cruzdeossos.com.br'],
            [
                'name' => 'Barnabé',
                'password' => Hash::make('cruzdeossos2025'),
            ]
        );
        $admin->assignRole('Presidente');
    }
}
