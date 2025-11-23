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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Unique invoice number
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Invoice belongs to a client
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Invoice belongs to a user
            $table->date('issue_date'); // Invoice issue date
            $table->date('due_date'); // Payment due date
            $table->decimal('subtotal', 10, 2)->default(0); // Subtotal before tax
            $table->decimal('tax', 10, 2)->default(0); // Tax amount
            $table->decimal('total', 10, 2)->default(0); // Total amount (subtotal + tax)
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft'); // Invoice status
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
