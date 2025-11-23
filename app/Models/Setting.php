<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        // Company Settings
        'company_name',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'tax_id',
        // Invoice Settings
        'invoice_prefix',
        'invoice_start_number',
        'default_tax_rate',
        'payment_terms',
        'invoice_footer',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'default_tax_rate' => 'decimal:2',
        'invoice_start_number' => 'integer',
    ];

    /**
     * Get the user that owns the settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
