<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Store $store): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMitraToko()) {
            return $user->id === $store->user_id;
        }

        return $store->is_active;
    }

    public function update(User $user, Store $store): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isMitraToko() && $user->id === $store->user_id;
    }
}
