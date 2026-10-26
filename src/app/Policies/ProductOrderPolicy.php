<?php

namespace App\Policies;

use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProductOrder $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isPelanggan()) {
            return $user->id === $order->customer_id;
        }

        if ($user->isMitraToko()) {
            return $user->store !== null && $user->store->id === $order->store_id;
        }

        return false;
    }

    public function update(User $user, ProductOrder $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->store !== null && $user->store->id === $order->store_id;
        }

        return false;
    }
}
