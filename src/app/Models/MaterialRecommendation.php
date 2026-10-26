<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'notes',
        'revision_count',
    ];

    protected function casts(): array
    {
        return [
            'revision_count' => 'integer',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialRecommendationItem::class);
    }
}
