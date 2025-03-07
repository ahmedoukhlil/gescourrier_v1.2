<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ReplaceDestinataireWithUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Ajouter le champ 'service' à la table users
        Schema::table('users', function (Blueprint $table) {
            $table->string('service')->nullable()->after('email');
        });

        // 2. Migrer les données de destinataires vers users (si nécessaire)
        // Cette étape dépend de la situation - si les destinataires ont déjà des comptes
        // utilisateurs correspondants ou s'il faut en créer de nouveaux
        
        // 3. Modifier la table courriers_entrants pour remplacer destinataire_id
        Schema::table('courriers_entrants', function (Blueprint $table) {
            // Créer une nouvelle colonne
            $table->unsignedBigInteger('user_id')->nullable()->after('destinataire_id');
            
            // Ajouter la contrainte de clé étrangère
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
        
        // 4. Migrer les données de destinataire_id à user_id (à adapter selon votre logique d'application)
        // Ceci est un exemple basique - la logique exacte pourrait varier
        
        // 5. Vérifier la structure de la table pivot existante
        // Nous vérifions d'abord le nom de la table référencée
        if (Schema::hasTable('courriers_entrants')) {
            // Courriers_entrants existe, nous supposons que la table est "courriers_entrants" et non "courriers"
            
            // Créer une nouvelle table courrier_user
            Schema::create('courrier_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('courrier_entrant_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                
                $table->foreign('courrier_entrant_id')->references('id')->on('courriers_entrants')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        } else {
            // Si courriers_entrants n'existe pas, nous vérifions si la table est "courriers"
            if (Schema::hasTable('courriers')) {
                Schema::create('courrier_user', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('courrier_id');
                    $table->unsignedBigInteger('user_id');
                    $table->timestamps();
                    
                    $table->foreign('courrier_id')->references('id')->on('courriers')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } else {
                // Si ni courriers ni courriers_entrants n'existent, créer la table sans contraintes
                Schema::create('courrier_user', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('courrier_id');
                    $table->unsignedBigInteger('user_id');
                    $table->timestamps();
                });
            }
        }
        
        // 6. Supprimer l'ancienne colonne destinataire_id de courriers_entrants
        try {
            Schema::table('courriers_entrants', function (Blueprint $table) {
                $table->dropForeign(['destinataire_id']);
            });
        } catch (\Exception $e) {
            // La clé étrangère n'existe peut-être pas ou a un nom différent
            // Essayer avec le nom complet de la clé
            try {
                DB::statement('ALTER TABLE courriers_entrants DROP FOREIGN KEY courriers_entrants_destinataire_id_foreign');
            } catch (\Exception $e) {
                // Ignorer si ça échoue aussi
            }
        }
        
        Schema::table('courriers_entrants', function (Blueprint $table) {
            $table->dropColumn('destinataire_id');
        });
        
        // 7. Supprimer l'ancienne table d'association (en tenant compte du fait qu'elle pourrait ne pas exister)
        Schema::dropIfExists('courrier_destinataire');
        
        // 8. Finalement, supprimer la table destinataires
        Schema::dropIfExists('destinataires');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rétablir la structure originale
        // Recréer la table destinataires
        Schema::create('destinataires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('service');
            $table->string('email')->nullable();
            $table->timestamps();
        });
        
        // Recréer la table intermédiaire
        Schema::create('courrier_destinataire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courrier_id');
            $table->unsignedBigInteger('destinataire_id');
            $table->timestamps();
            
            if (Schema::hasTable('courriers')) {
                $table->foreign('courrier_id')->references('id')->on('courriers')->onDelete('cascade');
            } else if (Schema::hasTable('courriers_entrants')) {
                $table->foreign('courrier_id')->references('id')->on('courriers_entrants')->onDelete('cascade');
            }
            
            $table->foreign('destinataire_id')->references('id')->on('destinataires')->onDelete('cascade');
        });
        
        // Recréer la relation dans courriers_entrants
        Schema::table('courriers_entrants', function (Blueprint $table) {
            $table->unsignedBigInteger('destinataire_id')->nullable()->after('objet');
            $table->foreign('destinataire_id')->references('id')->on('destinataires');
        });
        
        // Supprimer le champ service de users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('service');
        });
        
        // Retirer user_id de courriers_entrants
        Schema::table('courriers_entrants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        
        // Supprimer la nouvelle table intermédiaire
        Schema::dropIfExists('courrier_user');
    }
}