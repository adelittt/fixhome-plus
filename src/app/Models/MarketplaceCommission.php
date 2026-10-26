<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_order_id',
        'store_id',
        'gross_product_amount',
        'platform_rate',
        'platform_commission',
        'store_earning',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'gross_product_amount' => 'integer',
            'platform_rate' => 'float',
            'platform_commission' => 'integer',
            'store_earning' => 'integer',
        ];
    }

    public function productOrder(): BelongsTo
    {
        return $this->belongsTo(ProductOrder::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
