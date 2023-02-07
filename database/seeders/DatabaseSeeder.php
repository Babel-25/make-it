<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Etat;
use App\Models\Level;
use App\Models\Membre;
use App\Models\Montant;
use App\Models\Paiement;
use App\Models\Personne;
use App\Models\Phase;
use App\Models\Sexe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $etat_actif = Etat::firstOrCreate([
            'code'    => 'ACT',
            'libelle' => 'Actif'
        ]);
        $etat_inactif = Etat::firstOrCreate([
            'code'    => 'INA',
            'libelle' => 'Inactif'
        ]);

        $sexe_masculin = Sexe::firstOrCreate([
            'code'    => 'M',
            'libelle' => 'Masculin'
        ]);
        $sexe_feminin = Sexe::firstOrCreate([
            'code'    => 'F',
            'libelle' => 'Féminin'
        ]);
        $paiement1 =Paiement::firstOrCreate([
            'code_paiement'    => Str::random(10),
            'libelle_paiement' => 'Paiement montant 3000 F CFA',
            'montant_paiement' => 3000,
            'status'           => 0
        ]);

        $paiement = Paiement::factory(5)->create();

        $phase1 = Phase::firstOrCreate([
            'ref_phase'     => Str::random(16),
            'libelle_phase' => 'Phase A',
            'total_niveau'  => 4,
        ]);

        $phase1_level0 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 0',
            'total_membre'   => 0,
            'phase_id'       => $phase1->id
        ]);

        $phase1_level1 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 1',
            'total_membre'   => 2,
            'phase_id'       => $phase1->id
        ]);

        $phase1_level2 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 2',
            'total_membre'   => 4,
            'phase_id'       => $phase1->id
        ]);

        $phase1_level3 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 3',
            'total_membre'   => 8,
            'phase_id'       => $phase1->id
        ]);

        $phase1_level4 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 4',
            'total_membre'   => 16,
            'phase_id'       => $phase1->id
        ]);

        $phase2 = Phase::firstOrCreate([
            'ref_phase'     => Str::random(16),
            'libelle_phase' => 'Phase B',
            'total_niveau'  => 4,
        ]);

        $phase2_level0 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 0',
            'total_membre'   => 0,
            'phase_id'       => $phase2->id
        ]);

        $phase2_level1 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 1',
            'total_membre'   => 2,
            'phase_id'       => $phase2->id
        ]);

        $phase2_level2 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 2',
            'total_membre'   => 4,
            'phase_id'       => $phase2->id
        ]);

        $phase2_level3 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 3',
            'total_membre'   => 8,
            'phase_id'       => $phase2->id
        ]);

        $phase2_level4 = Level::firstOrCreate([
            'ref_niveau'     => strtoupper(Str::random(16)),
            'libelle_niveau' => 'Niveau 4',
            'total_membre'   => 16,
            'phase_id'       => $phase2->id
        ]);

        $user = User::firstOrCreate([
            'identifiant' => 'liph',
            'password'    => Hash::make('philippes'),
            'status'      => 'Admin',
            'etat_id'     => $etat_inactif->id
        ]);

        $person = Personne::firstOrCreate(
            [
                'nom_prenom'      => 'FOLY Jacques-Philippes',
                'code_parrainage' => 'supreme',
                'lien_parrainage' => '1010',
                'adresse'         => 'Deckon',
                'contact'         => '+228 92 00 93 58',
                'date_naissance'  => '1998-4-1',
                'email'           => 'liphzone@gmail.com',
                'sexe_id'         => $sexe_masculin->id,
                'paiement_id'     => $paiement1->id,
                //Recuperation de l'id user
                'user_id'         => $user->id
            ]
        );

        $montant = Montant::create(
            [
                'montant_parrain' => 0,
                'montant_net'     => 0,
                'montant_total'   => 0,
                'personne_id'     => $person->id
            ]
        );

        if($person->code_parrainage === 'supreme'){}

        $membre = Membre::firstOrCreate([
            'ref_membre'  => Str::random(20),
            'phase_id'    => $phase1->id,
            'level_id'     => $phase1_level0->id,
            'personne_id' => $person->id,
            'parrain'     => 0
        ]);
    }
}
