<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phase>
 */
class PhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $array = ['A','B','C'];
        $niveau = [1,2,3,4];
        return [
            'ref_phase'     => Str::random(16),
            'libelle_phase' => 'Phase ' .Arr::random($array),
            'total_niveau'  => Arr::random($niveau),
        ];
    }
}
