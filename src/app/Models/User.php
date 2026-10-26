<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'role',
        'phone',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isMitraToko(): bool
    {
        return $this->role === UserRole::MITRA_TOKO;
    }

    public function isTeknisi(): bool
    {
        return $this->role === UserRole::TEKNISI;
    }

    public function isPelanggan(): bool
    {
        return $this->role === UserRole::PELANGGAN;
    }

    public function technicianProfile(): HasOne
    {
        return $this->hasOne(TechnicianProfile::class);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function customerServiceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'customer_id');
    }

    public function technicianServiceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'technician_id');
    }

    public function productOrders(): HasMany
    {
        return $this->hasMany(ProductOrder::class, 'customer_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'customer_id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        }

        $hash = md5(strtolower(trim($this->email)));
        return 'https://www.gravatar.com/avatar/' . $hash . '?d=mp&r=g&s=250';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->isAdmin();
        }

        if ($panel->getId() === 'toko') {
            return $this->isMitraToko();
        }

        return false;
    }
}
