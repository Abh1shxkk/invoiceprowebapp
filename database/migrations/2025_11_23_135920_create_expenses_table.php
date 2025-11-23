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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Expense belongs to a user
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Expense belongs to a category
            $table->decimal('amount', 10, 2)->default(0); // Expense amount
            $table->date('date'); // Expense date
            $table->text('description')->nullable(); // Expense description
            $table->string('receipt_path')->nullable(); // Path to receipt file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
