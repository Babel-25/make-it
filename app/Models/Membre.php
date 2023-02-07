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
        'level_id',
        'personne_id',
        'parrain'
    ];
}
