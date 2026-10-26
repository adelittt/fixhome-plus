<?php

namespace App\Policies;

use App\Models\ServiceCommission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceCommissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Admin dapat melihat semua catatan komisi jasa; teknisi dapat melihat bagiannya (PRD 8)
        return $user->isAdmin() || $user->isTeknisi();
    }

    public function view(User $user, ServiceCommission $commission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeknisi()) {
            return $user->id === $commission->technician_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Komisi dibuat otomatis oleh sistem saat alur jasa selesai
        return false;
    }

    public function update(User $user, ServiceCommission $commission): bool
    {
        return false;
    }

    public function delete(User $user, ServiceCommission $commission): bool
    {
        return false;
    }
}
