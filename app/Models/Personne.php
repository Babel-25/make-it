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
        'user_id',
        'paiement_id',
    ];

    public function sexe(){
        return $this->belongsTo(Sexe::class);
    }


    //Une personne possède un seul et unique compte
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    //Une personne possède un seul sexe
    // public function sexe(){
    //     return $this->hasOne(Sexe::class);
    // }

    //Une personne possede 1.1 code de paiement
    // public function paiement(){
    //     return $this->hasOne(Paiement::class);
    // }

    //Une personne est membre unique
    // public function membre(){
    //     return $this->belongsTo(Membre::class);
    // }
}
