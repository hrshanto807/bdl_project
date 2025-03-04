<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['total', 'discount', 'vat', 'payable', 'user_id', 'customer_id'];

    /**
     * Relationship with Customer Model
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship with InvoiceProduct Model
     */
    public function invoiceProducts(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
