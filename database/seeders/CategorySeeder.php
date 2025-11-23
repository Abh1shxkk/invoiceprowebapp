<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Expense Categories
        $expenseCategories = [
            'Office Supplies',
            'Utilities',
            'Rent',
            'Salaries',
            'Marketing',
            'Travel',
            'Equipment',
            'Software & Subscriptions',
            'Insurance',
            'Miscellaneous',
        ];

        foreach ($expenseCategories as $category) {
            Category::create([
                'name' => $category,
                'type' => 'expense',
            ]);
        }

        // Income Categories
        $incomeCategories = [
            'Product Sales',
            'Service Revenue',
            'Consulting',
            'Commission',
            'Other Income',
        ];

        foreach ($incomeCategories as $category) {
            Category::create([
                'name' => $category,
                'type' => 'income',
            ]);
        }
    }
}
