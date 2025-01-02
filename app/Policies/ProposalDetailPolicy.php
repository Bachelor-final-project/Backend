<?php

namespace App\Policies;

use App\Models\ProposalDetail;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProposalDetailPolicy
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
     * @param  \App\Models\ProposalDetail  $proposalDetail
     * @return mixed
     */
    public function view(User $user, ProposalDetail $proposalDetail)
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
     * @param  \App\Models\ProposalDetail  $proposalDetail
     * @return mixed
     */
    public function update(User $user, ProposalDetail $proposalDetail)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProposalDetail  $proposalDetail
     * @return mixed
     */
    public function delete(User $user, ProposalDetail $proposalDetail)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProposalDetail  $proposalDetail
     * @return mixed
     */
    public function restore(User $user, ProposalDetail $proposalDetail)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProposalDetail  $proposalDetail
     * @return mixed
     */
    public function forceDelete(User $user, ProposalDetail $proposalDetail)
    {
        //
    }
}
