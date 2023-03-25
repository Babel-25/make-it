<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_prenom',
        'sexe_id',
        'adresse',
        'contact',
        'date_naissance',
        'email',
        'code_parrainage',
        'lien_parrainage',
        'user_id',
        'paiement_id',
    ];

    public function membre(){
        return $this->hasOne(Membre::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sexe()
    {
        return $this->belongsTo(Sexe::class);
    }
}
