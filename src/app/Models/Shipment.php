<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_order_id',
        'provider_name',
        'tracking_number',
        'origin_address',
        'destination_address',
        'shipping_cost',
        'estimated_hours',
        'status',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'integer',
            'estimated_hours' => 'float',
            'status' => ShipmentStatus::class,
            'delivered_at' => 'datetime',
        ];
    }

    public function productOrder(): BelongsTo
    {
        return $this->belongsTo(ProductOrder::class);
    }
}
