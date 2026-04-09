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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('event_id')->constrained(); 
            
            $table->foreignId('promo_code_id')->nullable()->constrained(); // reference to the promo code used, if any
            $table->decimal('discount_amount', 10, 2)->default(0); // record the discount amount applied from the promo code
            
            // payment details
            $table->decimal('total_amount', 10, 2); 
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable(); 
            $table->timestamp('paid_at')->nullable(); 
            
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
