<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioPadraoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Usuario::count() == 0) {
            Usuario::query()->create([
                'nome' => 'Administrador',
                'email' => 'admin@admin',
                'cpf' => '00000000000',
                'senha' => bcrypt('secret'),
                'municipio_id' => 1,
                'perfil' => 'admin',
            ]);
        }
    }
}
