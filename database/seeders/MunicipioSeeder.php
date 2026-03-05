<?php

namespace Database\Seeders;

use App\Models\Municipio\Municipio;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Municipio::count() == 0) {
            Municipio::query()->create([
                'nome' => 'Campo Alegre',
                'uf' => 'AL',
                'secretaria_responsavel' => 'Prefeitura',
            ]);
        }
    }
}
