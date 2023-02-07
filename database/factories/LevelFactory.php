<?php

namespace Database\Factories;

use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $niveau = [2,4,8,16];
        $membre = [2,4,8,16];
        return [
            'ref_niveau'     =>  strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau ' . Arr::random($niveau),
            'total_membre'   => Arr::random($membre),
            'phase_id'       => Phase::factory()->create()
        ];
    }
}
