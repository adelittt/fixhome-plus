<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TechnicianWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id',
        'withdrawal_code',
        'amount',
        'bank_name',
        'account_number',
        'account_holder_name',
        'notes',
        'status',
        'admin_notes',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function scopeForTechnician(Builder $query, int $technicianId): Builder
    {
        return $query->where('technician_id', $technicianId);
    }

    public static function generateCode(): string
    {
        return 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    }
}
