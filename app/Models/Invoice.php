<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
        'client_id',
        'user_id',
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that owns the invoice.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client that owns the invoice.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the items for the invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the total paid amount for the invoice.
     */
    public function totalPaid()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get the remaining balance for the invoice.
     */
    public function balance()
    {
        return $this->total - $this->totalPaid();
    }
}
