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
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan foreign key ke users
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Tambahkan foreign key ke events
            $table->unsignedBigInteger('event_id')->after('user_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            
            // Tambahkan kolom tambahan yang diperlukan
            $table->string('reference_id')->unique()->after('event_id')->nullable();
            $table->string('payment_url')->nullable()->after('reference_id');
            $table->timestamp('paid_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['event_id']);
            $table->dropColumn(['user_id', 'event_id', 'reference_id', 'payment_url', 'paid_at']);
        });
    }
};
