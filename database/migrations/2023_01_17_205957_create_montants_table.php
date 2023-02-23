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
            //Id Phase
            $table->unsignedBigInteger('phase_id')->index();
            $table->foreign('phase_id')->references('id')->on('phases')->onUpdate('cascade')->onDelete('cascade');

            //Id Personne
            $table->unsignedBigInteger('personne_id')->index();
            $table->foreign('personne_id')->references('id')->on('personnes')->onUpdate('cascade')->onDelete('cascade');

            $table->float('gain_parrainage')->nullable();
            $table->float('gain_niv1')->nullable();
            $table->float('gain_niv2')->nullable();
            $table->float('gain_niv3')->nullable();
            $table->float('gain_niv4')->nullable();
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
