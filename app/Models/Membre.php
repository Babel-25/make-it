<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_membre',
        'phase_id',
        'niveau_id',
        'personne_id',
    ];

    //Un membre est dans une seule et unique phase
    public function phase(){
        return $this->belongsTo(Phase::class);
    }

    //Un membre est dans un seul et unique niveau(1.1)
    public function niveau(){
        return $this->hasOne(Niveau::class);
    }

    //plusieurs personnes peuvent etres membres
    public function personnes(){
        return $this->hasMany(Personne::class);
    }
}
