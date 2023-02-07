<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Personne;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personne>
 */
class PersonneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom_prenom'      => fake()->name(),
            'contact'         => fake()->phoneNumber(),
            'adresse'         => fake()->address(),
            'date_naissance'  => Date('2000-10-4'),
            'email'           => fake()->unique()->safeEmail(),
            'code_parrainage' => '1010',
            'user_id'         => User::factory()->create(),
            'paiement_id'     => Paiement::factory()->create(),
            'sexe_id'         => 1,
        ];
    }
}
