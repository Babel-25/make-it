<?php

use App\Models\Membre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\User;

function getDescendants($userId) {
    $descendants = Membre::where('parrain', $userId)->get();

    if ($descendants->isEmpty()) {
        return collect([]);
    }

    $allDescendants = collect([]);

    foreach ($descendants as $descendant) {
        $allDescendants->push($descendant);
        $allDescendants = $allDescendants->concat(getDescendants($descendant->id));
    }

    return $allDescendants;
}
