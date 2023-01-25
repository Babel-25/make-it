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
        Schema::create('montants', function (Blueprint $table) {
            $table->id();
            $table->float('montant_parrain')->nullable();
            $table->float('montant_net')->nullable();
            $table->float('montant_total')->nullable();

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
        Schema::dropIfExists('montants');
    }
};
