<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Admin, Mitra Toko, Pelanggan, dan Teknisi dapat melihat daftar produk
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->store?->id === $product->store_id;
        }

        // Pelanggan dan teknisi hanya dapat melihat produk aktif
        return $product->is_active;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || ($user->isMitraToko() && $user->store !== null);
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->store !== null && $user->store->id === $product->store_id;
        }

        return false;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->store !== null && $user->store->id === $product->store_id;
        }

        return false;
    }
}
