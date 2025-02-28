<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourrierDestinataireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courrier_destinataire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courrier_id')->constrained('courriers_entrants')->onDelete('cascade');
            $table->foreignId('destinataire_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Pour éviter les doublons
            $table->unique(['courrier_id', 'destinataire_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courrier_destinataire');
    }
}