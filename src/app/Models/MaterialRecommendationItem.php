<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRecommendationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_recommendation_id',
        'product_id',
        'material_name',
        'specification',
        'quantity',
        'unit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function recommendation(): BelongsTo
    {
        return $this->belongsTo(MaterialRecommendation::class, 'material_recommendation_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
