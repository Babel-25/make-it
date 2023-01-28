<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etat extends Model
{
    use HasFactory;

    Protected $fillable = [
        'code',
        'libelle',
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
}
