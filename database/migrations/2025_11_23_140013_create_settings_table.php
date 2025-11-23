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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Settings belong to a user
            
            // Company Settings
            $table->string('company_name')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable();
            
            // Invoice Settings
            $table->string('invoice_prefix')->default('INV');
            $table->integer('invoice_start_number')->default(1);
            $table->decimal('default_tax_rate', 5, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->text('invoice_footer')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
