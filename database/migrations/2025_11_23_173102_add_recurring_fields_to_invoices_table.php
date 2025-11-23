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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->enum('recurring_frequency', ['weekly', 'monthly', 'quarterly', 'yearly'])->nullable()->after('is_recurring');
            $table->date('recurring_start_date')->nullable()->after('recurring_frequency');
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            $table->date('last_recurring_date')->nullable()->after('recurring_end_date');
            $table->foreignId('parent_invoice_id')->nullable()->constrained('invoices')->onDelete('set null')->after('last_recurring_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_invoice_id']);
            $table->dropColumn([
                'is_recurring',
                'recurring_frequency',
                'recurring_start_date',
                'recurring_end_date',
                'last_recurring_date',
                'parent_invoice_id',
            ]);
        });
    }
};
