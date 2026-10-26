<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicianProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_category_id',
        'bio',
        'base_rate',
        'verification_status',
        'is_available',
        'address',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'base_rate' => 'integer',
            'verification_status' => VerificationStatus::class,
            'is_available' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(TechnicianService::class);
    }
}
