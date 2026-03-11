<?php

namespace Database\Factories;

use App\Enums\Perfil;
use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'email' => fake()->unique()->safeEmail(),
            'senha' => Hash::make('password'),
            'municipio_id' => Municipio::factory(),
            'perfil' => fake()->randomElement(Perfil::cases()),
        ];
    }
}
