<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourrierSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courrier_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courrier_entrant_id')->constrained('courriers_entrants')->onDelete('cascade');
            $table->foreignId('shared_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('shared_with')->constrained('users')->onDelete('cascade');
            $table->foreignId('annotation_id')->nullable()->constrained('courrier_annotations')->onDelete('set null');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Empêcher les doublons
            $table->unique(['courrier_entrant_id', 'shared_with']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courrier_shares');
    }
}