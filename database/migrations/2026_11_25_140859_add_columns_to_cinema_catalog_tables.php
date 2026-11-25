<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('genre', 100)->nullable();
            $table->string('rating', 20)->nullable();
            $table->unsignedInteger('duration_minutes');
            $table->string('poster_url', 2048)->nullable();
            $table->string('status', 20)->default('active')->index();
        });

        Schema::table('auditoria', function (Blueprint $table) {
            $table->string('name', 100);
            $table->unsignedInteger('capacity');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->foreignId('auditorium_id')->constrained('auditoria')->cascadeOnDelete();
            $table->string('row_label', 10);
            $table->unsignedInteger('seat_number');
            $table->string('seat_type', 20)->default('standard');
            $table->unique(['auditorium_id', 'row_label', 'seat_number']);
        });

        Schema::table('showtimes', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->foreignId('auditorium_id')->constrained('auditoria')->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->decimal('base_price', 8, 2);
            $table->string('status', 20)->default('scheduled')->index();
            $table->index('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['starts_at']);
            $table->dropForeign(['movie_id']);
            $table->dropForeign(['auditorium_id']);
            $table->dropColumn(['movie_id', 'auditorium_id', 'starts_at', 'ends_at', 'base_price', 'status']);
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropUnique(['auditorium_id', 'row_label', 'seat_number']);
            $table->dropForeign(['auditorium_id']);
            $table->dropColumn(['auditorium_id', 'row_label', 'seat_number', 'seat_type']);
        });

        Schema::table('auditoria', function (Blueprint $table) {
            $table->dropColumn(['name', 'capacity']);
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['title', 'description', 'genre', 'rating', 'duration_minutes', 'poster_url', 'status']);
        });
    }
};
