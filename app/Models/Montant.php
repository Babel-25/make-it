<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Montant extends Model
{
    use HasFactory;

    protected $fillable = ['montant_parrain', 'montant_net', 'montant_total', 'personne_id'];
}
