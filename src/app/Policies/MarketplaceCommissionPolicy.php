<?php

namespace App\Policies;

use App\Models\MarketplaceCommission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceCommissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Admin dapat melihat semua catatan komisi; Mitra Toko dapat melihat bagian miliknya (PRD 8)
        return $user->isAdmin() || $user->isMitraToko();
    }

    public function view(User $user, MarketplaceCommission $commission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->store !== null && $user->store->id === $commission->store_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Komisi dibuat otomatis oleh sistem saat pesanan produk berstatus selesai (PK-04)
        return false;
    }

    public function update(User $user, MarketplaceCommission $commission): bool
    {
        return false;
    }

    public function delete(User $user, MarketplaceCommission $commission): bool
    {
        return false;
    }
}
