<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periodo>
 */
class PeriodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'municipio_id' => \App\Models\Municipio\Municipio::factory(),
            'descricao' => $this->faker->word(),
            'inicio_inscricao' => now()->addDays(1),
            'fim_inscricao' => now()->addDays(15),
            'inicio' => now()->addDays(16),
            'fim' => now()->addDays(150),
        ];
    }
}
