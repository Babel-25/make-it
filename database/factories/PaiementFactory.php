<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code_paiement'    => random_int(1000,100000000),
            'libelle_paiement' => 'Paiement montant 3000 F CFA',
            'montant_paiement' => 3000,
            'status'           => 0
        ];
    }
}
