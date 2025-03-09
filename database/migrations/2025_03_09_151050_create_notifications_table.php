<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('link')->nullable();
            $table->string('type')->default('info'); // info, success, warning, error
            $table->boolean('is_read')->default(false);
            $table->tinyInteger('importance')->default(1); // 1-5 scale
            $table->string('source_type')->nullable(); // Polymorphic relation type
            $table->unsignedBigInteger('source_id')->nullable(); // Polymorphic relation ID
            $table->unsignedBigInteger('created_by')->nullable(); // User who triggered the notification
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index(['user_id', 'is_read']);
            $table->index(['source_type', 'source_id']);
            $table->index('created_by');
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('browser_notifications')->default(true);
            $table->json('notification_types')->nullable();
            $table->tinyInteger('minimum_importance')->default(1); // 1-5 scale
            $table->boolean('daily_digest')->default(false);
            $table->string('daily_digest_time')->default('09:00');
            $table->timestamp('pause_until')->nullable();
            $table->timestamps();

            // Ensure only one preferences record per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
}