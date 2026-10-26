<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicianService extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_profile_id',
        'name',
        'description',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function technicianProfile(): BelongsTo
    {
        return $this->belongsTo(TechnicianProfile::class);
    }
}
