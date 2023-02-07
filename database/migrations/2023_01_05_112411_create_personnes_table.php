<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_prenom');
            $table->string('contact');
            $table->string('adresse');
            $table->string('date_naissance');
            $table->string('email')->unique();
            $table->string('code_parrainage');
            $table->string('lien_parrainage');

            //Id User
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            //Id Paiement
            $table->unsignedBigInteger('paiement_id')->index();
            $table->foreign('paiement_id')->references('id')->on('paiements')->onUpdate('cascade')->onDelete('cascade');
            //Id Sexe
            $table->unsignedBigInteger('sexe_id')->index();
            $table->foreign('sexe_id')->references('id')->on('sexes')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personnes');
    }
};
