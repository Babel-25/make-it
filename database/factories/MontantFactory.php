<?php

namespace Database\Factories;

use App\Models\Personne;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Montant>
 */
class MontantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'montant_parrain' => 0,
            'montant_net'     => 0,
            'montant_total'   => 0,
            'personne_id'     => Personne::factory()->create(),
        ];
    }
}
