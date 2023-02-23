<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = ['ref_niveau', 'libelle_niveau', 'total_membre', 'phase_id'];

    public function phase(){
        return $this->belongsTo(Phase::class);
    }
}
