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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // Payment belongs to an invoice
            $table->decimal('amount', 10, 2)->default(0); // Payment amount
            $table->date('payment_date'); // Date of payment
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'paypal', 'other'])->default('cash'); // Payment method
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
