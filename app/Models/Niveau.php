<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;
    protected $fillable = ['ref_niveau', 'libelle_niveau', 'total_membre', 'phase_id'];

    // Un niveau appartient a une seule et une unique phase
    public function phase(){
        return $this->belongsTo(Phase::class);
    }

    //Un niveau comporte  un ou plusieurs membres
    public function membres(){
        return $this->belongsToMany(Membre::class);
    }
}
