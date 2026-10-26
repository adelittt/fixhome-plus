<?php

namespace App\Models;

use App\Enums\ProductOrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProductOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'service_order_id',
        'customer_id',
        'store_id',
        'total_product_amount',
        'shipping_fee',
        'grand_total',
        'status',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'total_product_amount' => 'integer',
            'shipping_fee' => 'integer',
            'grand_total' => 'integer',
            'status' => ProductOrderStatus::class,
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductOrderItem::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function marketplaceCommission(): HasOne
    {
        return $this->hasOne(MarketplaceCommission::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function scopeForStore(Builder $query, int $storeId): Builder
    {
        return $query->where('store_id', $storeId);
    }
}
