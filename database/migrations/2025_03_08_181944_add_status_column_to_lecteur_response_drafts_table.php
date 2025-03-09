<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToLecteurResponseDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lecteur_response_drafts', function (Blueprint $table) {
            // Ajouter la colonne status si elle n'existe pas déjà
            if (!Schema::hasColumn('lecteur_response_drafts', 'status')) {
                $table->enum('status', ['draft', 'pending', 'revised', 'approved'])
                    ->default('draft')
                    ->after('is_reviewed');
            }
            
            // Ajouter la colonne needs_revision si elle n'existe pas déjà
            if (!Schema::hasColumn('lecteur_response_drafts', 'needs_revision')) {
                $table->boolean('needs_revision')
                    ->default(false)
                    ->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lecteur_response_drafts', function (Blueprint $table) {
            // Supprimer les colonnes si elles existent
            if (Schema::hasColumn('lecteur_response_drafts', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('lecteur_response_drafts', 'needs_revision')) {
                $table->dropColumn('needs_revision');
            }
        });
    }
}