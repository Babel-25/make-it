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

            //Id Niveau(Level)
            $table->unsignedBigInteger('level_id')->index();
            $table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');

            //Id Personne
            $table->unsignedBigInteger('personne_id')->index();
            $table->foreign('personne_id')->references('id')->on('personnes')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('parrain');

            $table->integer('position');

            $table->boolean('etat');
            $table->enum('parrain_direct',['OUI','NON','NULL'])->default('OUI');
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
