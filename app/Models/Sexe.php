<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sexe extends Model
{
    use HasFactory;

    Protected $fillable = [
        'code',
        'libelle',
    ];

    public function personne(){
        return $this->hasOne(Personne::class);
    }

}
