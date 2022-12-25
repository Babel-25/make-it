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
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->string('ref_membre');

            //Id Phase
            $table->unsignedBigInteger('phase_id')->index();
            $table->foreign('phase_id')->references('id')->on('phases')->onUpdate('cascade')->onDelete('cascade');

            //Id Niveau
            $table->unsignedBigInteger('niveau_id')->index();
            $table->foreign('niveau_id')->references('id')->on('niveaux')->onUpdate('cascade')->onDelete('cascade');

            //Id Personne
            $table->unsignedBigInteger('personne_id')->index();
            $table->foreign('personne_id')->references('id')->on('personnes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('membres');
    }
};
