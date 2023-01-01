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
            $table->enum('sexe', ['M', 'F']);
            $table->string('contact');
            $table->string('adresse');
<<<<<<< HEAD
            $table->string('date_naissance');
=======
>>>>>>> 2cc119af9e396a4818755a869b0d4ba0a94cf550
            $table->string('email')->unique();
            $table->string('code_parrainage')->nullable();

            //Id User
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            //Id Paiement
            $table->unsignedBigInteger('paiement_id')->index();
            $table->foreign('paiement_id')->references('id')->on('paiements')->onUpdate('cascade')->onDelete('cascade');
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
