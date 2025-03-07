<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourrierAnnotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courrier_annotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courrier_entrant_id')->constrained('courriers_entrants')->onDelete('cascade');
            $table->foreignId('annotated_by')->constrained('users')->onDelete('cascade');
            $table->text('annotation');
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
        Schema::dropIfExists('courrier_annotations');
    }
}