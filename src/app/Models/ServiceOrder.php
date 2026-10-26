<?php

namespace App\Models;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'technician_id',
        'service_category_id',
        'job_description',
        'scheduled_at',
        'address',
        'latitude',
        'longitude',
        'distance_km',
        'base_service_fee',
        'distance_fee',
        'total_service_fee',
        'status',
        'marketplace_choice',
        'cancellation_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'distance_km' => 'float',
            'base_service_fee' => 'integer',
            'distance_fee' => 'integer',
            'total_service_fee' => 'integer',
            'status' => ServiceOrderStatus::class,
            'marketplace_choice' => MarketplaceChoice::class,
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function materialRecommendation(): HasOne
    {
        return $this->hasOne(MaterialRecommendation::class);
    }

    public function serviceCommission(): HasOne
    {
        return $this->hasOne(ServiceCommission::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
