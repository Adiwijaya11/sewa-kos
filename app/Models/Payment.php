<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'amount',
        'payment_type',
        'payment_status',
        'transaction_id',
        'va_number',
        'cancellation_fee',
        'cancelled_at',
        'cancellation_reason',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'cancellation_fee' => 'integer',
            'cancelled_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
