<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    //La colonne parrain recupere l'id de la personne mais ce n'est un lien comme level_id par exemple
    protected $fillable = [
        'ref_membre',
        'phase_id',
        'level_id',
        'personne_id',
        'parrain'
        ,'etat'
    ];
}
