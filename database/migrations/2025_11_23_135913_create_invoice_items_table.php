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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // Item belongs to an invoice
            $table->string('description'); // Item description
            $table->integer('quantity')->default(1); // Item quantity
            $table->decimal('price', 10, 2)->default(0); // Unit price
            $table->decimal('total', 10, 2)->default(0); // Total (quantity * price)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
