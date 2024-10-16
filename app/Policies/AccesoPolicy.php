<?php

namespace App\Policies;

use App\Models\Acceso;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccesoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();

    }
}
