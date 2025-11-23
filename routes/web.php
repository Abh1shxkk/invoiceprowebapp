<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Redirect to appropriate dashboard if already logged in
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin') || auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes - Only Admin Role
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management Routes (Admin only)
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Reports Routes (Admin only)
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('reports.index');

    // Settings Routes (Admin only)
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| User Routes - Both Admin and User Roles
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user'])->prefix('dashboard')->name('user.')->group(function () {
    // User Dashboard
    Route::get('/', [App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');

    // Client Management Routes (Full CRUD)
    Route::resource('clients', App\Http\Controllers\User\ClientController::class);

    // Invoice Management Routes (Full CRUD)
    Route::resource('invoices', App\Http\Controllers\User\InvoiceController::class);
    Route::get('/invoices/{invoice}/pdf', [App\Http\Controllers\User\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/mark-paid', [App\Http\Controllers\User\InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::post('/invoices/{invoice}/mark-sent', [App\Http\Controllers\User\InvoiceController::class, 'markAsSent'])->name('invoices.mark-sent');
    Route::post('/invoices/{invoice}/send-email', [App\Http\Controllers\User\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');


    // Expense Management Routes (Full CRUD)
    Route::resource('expenses', App\Http\Controllers\User\ExpenseController::class);

    // Category Management Routes (Full CRUD)
    Route::resource('categories', App\Http\Controllers\User\CategoryController::class);

    // Payment Management Routes (Full CRUD)
    Route::resource('payments', App\Http\Controllers\User\PaymentController::class);

    // Reports & Analytics Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\User\ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [App\Http\Controllers\User\ReportController::class, 'revenue'])->name('revenue');
        Route::get('/expenses', [App\Http\Controllers\User\ReportController::class, 'expenses'])->name('expenses');
        Route::get('/profit-loss', [App\Http\Controllers\User\ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/clients', [App\Http\Controllers\User\ReportController::class, 'clients'])->name('clients');
        
        // PDF Exports
        Route::get('/revenue/pdf', [App\Http\Controllers\User\ReportController::class, 'exportRevenuePdf'])->name('revenue.pdf');
        Route::get('/expenses/pdf', [App\Http\Controllers\User\ReportController::class, 'exportExpensePdf'])->name('expenses.pdf');
        Route::get('/profit-loss/pdf', [App\Http\Controllers\User\ReportController::class, 'exportProfitLossPdf'])->name('profit-loss.pdf');
    });

    // User Settings Routes
    Route::get('/settings', [App\Http\Controllers\User\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/company', [App\Http\Controllers\User\SettingsController::class, 'updateCompany'])->name('settings.company.update');
    Route::post('/settings/invoice', [App\Http\Controllers\User\SettingsController::class, 'updateInvoice'])->name('settings.invoice.update');
    Route::post('/settings/profile', [App\Http\Controllers\User\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/password', [App\Http\Controllers\User\SettingsController::class, 'updatePassword'])->name('settings.password.update');
});

/*
|--------------------------------------------------------------------------
| Profile Routes - All Authenticated Users
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

