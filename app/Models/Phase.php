<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_phase',
        'libelle_phase',
        'total_niveau',
    ];

    public function levels(){
        return $this->HasMany(Level::class);
    }

}
