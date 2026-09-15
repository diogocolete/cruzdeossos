<?php

namespace Database\Seeders;

use App\Models\Integrante;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $barnabe = Integrante::where('apelido', 'Barnabé')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@cruzdeossos.com.br'],
            [
                'name' => 'Barnabé',
                'password' => Hash::make('cruzdeossos2025'),
            ]
        );

        if (! $admin->hasRole('Presidente')) {
            $admin->assignRole('Presidente');
        }

        // Vincula o usuário admin ao integrante (login admin ↔ membro)
        if ($barnabe && $admin->integrante_id !== $barnabe->id) {
            $admin->update(['integrante_id' => $barnabe->id]);
        }
    }
}
