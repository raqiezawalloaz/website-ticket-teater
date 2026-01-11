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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique(); 
            $table->string('ticket_code')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('event_name'); 
            $table->string('ticket_category'); 
            $table->string('seat_number')->nullable(); 
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'expired', 'deny'])->default('pending');
            $table->boolean('is_checked_in')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
