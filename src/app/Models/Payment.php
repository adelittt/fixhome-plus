<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'payable_type',
        'payable_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
