<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_phase',
        'libelle_phase',
        'total_niveau',
    ];

    //Une phase possede 1 ou plusieurs niveaux(1.*)
    public function Niveaux(){
        return $this->hasMany(Niveau::class);
    }

    //Une phase comporte 1 ou plusieurs membres
    public function Membres(){
        return $this->hasMany(Membre::class);
    }
}
