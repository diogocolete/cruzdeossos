<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view integrantes', 'create integrantes', 'edit integrantes', 'delete integrantes',
            'view eventos', 'create eventos', 'edit eventos', 'delete eventos',
            'view noticias', 'create noticias', 'edit noticias', 'delete noticias',
            'view galeria', 'create galeria', 'edit galeria', 'delete galeria',
            'view depoimentos', 'create depoimentos', 'edit depoimentos', 'delete depoimentos',
            'view posts', 'create posts', 'edit posts', 'delete posts',
            'view fichas', 'edit fichas',
            'view inscricoes', 'edit inscricoes',
            'manage usuarios', 'manage permissoes', 'manage configuracoes',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $presidente = Role::firstOrCreate(['name' => 'Presidente']);
        $secretario = Role::firstOrCreate(['name' => 'Secretário']);
        $sargente = Role::firstOrCreate(['name' => 'Sargente de Armas']);
        $membro = Role::firstOrCreate(['name' => 'Membro']);

        $presidente->givePermissionTo(Permission::all());

        $secretario->givePermissionTo([
            'view integrantes', 'view eventos', 'view galeria', 'view depoimentos',
            'create eventos', 'edit eventos', 'delete eventos',
            'create noticias', 'edit noticias', 'delete noticias',
            'create depoimentos', 'edit depoimentos', 'delete depoimentos',
        ]);

        $sargente->givePermissionTo([
            'view integrantes', 'create integrantes', 'edit integrantes',
            'view eventos', 'view noticias', 'view depoimentos',
            'view galeria', 'create galeria', 'edit galeria',
        ]);

        $membro->givePermissionTo([
            'view integrantes', 'view eventos', 'view noticias', 'view galeria', 'view depoimentos',
        ]);
    }
}
