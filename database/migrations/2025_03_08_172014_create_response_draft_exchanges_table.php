<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseDraftExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_draft_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draft_id')->constrained('lecteur_response_drafts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->string('file_path')->nullable();
            $table->enum('type', ['feedback', 'revision']);
            $table->timestamps();
            // Dans la migration, ajoutez ces champs
            $table->enum('status', ['draft', 'pending', 'revised', 'approved'])->default('draft');
            $table->boolean('needs_revision')->default(false);
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('response_draft_exchanges');
    }
}