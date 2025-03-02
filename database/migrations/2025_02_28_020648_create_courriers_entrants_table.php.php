<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourriersEntrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courriers_entrants', function (Blueprint $table) {
            $table->id();
            $table->string('expediteur');
            $table->string('numero_ordre')->unique();
            $table->enum('type', ['urgent', 'confidentiel', 'normal']);
            $table->string('objet');
            $table->foreignId('destinataire_id')->constrained();
            $table->enum('statut', ['en_cours', 'traite', 'archive'])->default('en_cours');
            $table->string('nom_dechargeur');
            $table->string('document_path')->nullable();
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
        Schema::dropIfExists('courriers_entrants');
    }
}