<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'technician_id',
        'gross_amount',
        'platform_rate',
        'platform_commission',
        'technician_earning',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'integer',
            'platform_rate' => 'float',
            'platform_commission' => 'integer',
            'technician_earning' => 'integer',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
