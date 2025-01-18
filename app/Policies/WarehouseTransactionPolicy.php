<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseTransaction  $warehouseDetail
     * @return mixed
     */
    public function view(User $user, WarehouseTransaction $warehouseDetail)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseTransaction  $warehouseDetail
     * @return mixed
     */
    public function update(User $user, WarehouseTransaction $warehouseDetail)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseTransaction  $warehouseDetail
     * @return mixed
     */
    public function delete(User $user, WarehouseTransaction $warehouseDetail)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseTransaction  $warehouseDetail
     * @return mixed
     */
    public function restore(User $user, WarehouseTransaction $warehouseDetail)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseTransaction  $warehouseDetail
     * @return mixed
     */
    public function forceDelete(User $user, WarehouseTransaction $warehouseDetail)
    {
        //
    }
}
