<?php

namespace App\Policies;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceOrder $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isPelanggan()) {
            return $user->id === $order->customer_id;
        }

        if ($user->isTeknisi()) {
            return $user->id === $order->technician_id;
        }

        return false;
    }

    public function update(User $user, ServiceOrder $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isPelanggan()) {
            return $user->id === $order->customer_id;
        }

        if ($user->isTeknisi()) {
            return $user->id === $order->technician_id;
        }

        return false;
    }
}
