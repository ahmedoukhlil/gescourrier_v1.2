<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourriersSortantsTable extends Migration
{
    public function up()
    {
        Schema::create('courriers_sortants', function (Blueprint $table) {
            $table->id();
            $table->string('objet');
            $table->string('destinataire');
            $table->string('numero')->unique();
            $table->date('date'); // Ajout du champ date
            $table->foreignId('courrier_entrant_id')->nullable()->constrained('courriers_entrants')->onDelete('set null');
            $table->string('decharge')->nullable();
            $table->boolean('decharge_manquante')->default(true); // Pour faciliter le suivi des décharges manquantes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courriers_sortants');
    }
}