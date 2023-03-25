<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Membre extends Model
{
    use HasFactory;

    //La colonne "parrain" recupere l'id de la personne mais ce n'est un lien comme level_id par exemple
    //La colonne "etat" est un booleen qui permet de marquer la fin d'une phase si la valeur passe à 1
    #La colonne  "parrain_direct"
    /**Elle comporte trois valeurs : 'OUI', 'NON', 'NULL'
     * OUI est utilisé si le fieul appartient au parrain direct
     * NON est utilisé si le fieul est enregistré sous un parrain indirect
     * NULL est utilisé pour les parrains supremes
     */

    protected $fillable = [
        'ref_membre',
        'phase_id',
        'level_id',
        'personne_id',
        'parrain',
        'position',
        'etat',
        'parrain_direct',
        'sponsor_link'
    ];

    public function personne(){
        return $this->belongsTo(Personne::class);
    }
}
