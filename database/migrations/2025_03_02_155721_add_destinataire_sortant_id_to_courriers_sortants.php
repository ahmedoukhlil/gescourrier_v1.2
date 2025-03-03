<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinataireSortantIdToCourriersSortants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courriers_sortants', function (Blueprint $table) {
            $table->foreignId('destinataire_sortant_id')
                  ->nullable()
                  ->after('courrier_entrant_id')
                  ->constrained('destinataires_sortants')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courriers_sortants', function (Blueprint $table) {
            $table->dropForeign(['destinataire_sortant_id']);
            $table->dropColumn('destinataire_sortant_id');
        });
    }
}