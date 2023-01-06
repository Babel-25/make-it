<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable  = [

        'code_paiement',
        'montant_parrainage',
        'montant_net',
        'montant_total',
        'date_paiement'
    ];

}
