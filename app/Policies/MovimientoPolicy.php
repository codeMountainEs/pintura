<?php

namespace App\Policies;

use App\Models\Movimiento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MovimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return true;
    }


}
