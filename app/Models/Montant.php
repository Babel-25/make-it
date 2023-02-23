<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Montant extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'personne_id',
        'gain_parrainage',
        'gain_niv1',
        'gain_niv2',
        'gain_niv3',
        'gain_niv4',
    ];
}
