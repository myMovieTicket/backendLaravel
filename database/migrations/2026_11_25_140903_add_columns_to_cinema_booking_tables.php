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
        Schema::table('seat_holds', function (Blueprint $table) {
            $table->foreignId('showtime_id')->constrained('showtimes')->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained('seats')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->dateTime('expires_at');
            $table->index(['showtime_id', 'status', 'expires_at']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('showtime_id')->constrained('showtimes')->cascadeOnDelete();
            $table->string('status', 20)->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
        });

        Schema::table('booking_items', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('showtime_id')->constrained('showtimes')->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained('seats')->cascadeOnDelete();
            $table->decimal('price', 8, 2);
            $table->unique(['booking_id', 'seat_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('method', 50);
            $table->string('status', 20)->default('pending');
            $table->string('reference', 100)->nullable()->unique();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('booking_item_id')->constrained('booking_items')->cascadeOnDelete();
            $table->string('code', 100)->unique();
            $table->string('status', 20)->default('valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['booking_item_id']);
            $table->dropColumn(['booking_id', 'booking_item_id', 'code', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['booking_id', 'amount', 'method', 'status', 'reference']);
        });

        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropUnique(['booking_id', 'seat_id']);
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['showtime_id']);
            $table->dropForeign(['seat_id']);
            $table->dropColumn(['booking_id', 'showtime_id', 'seat_id', 'price']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['showtime_id']);
            $table->dropColumn(['user_id', 'showtime_id', 'status', 'total_amount']);
        });

        Schema::table('seat_holds', function (Blueprint $table) {
            $table->dropIndex(['showtime_id', 'status', 'expires_at']);
            $table->dropForeign(['showtime_id']);
            $table->dropForeign(['seat_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['showtime_id', 'seat_id', 'user_id', 'status', 'expires_at']);
        });
    }
};
