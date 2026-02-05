<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class ProposalPolicy
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
     * @param  \App\Models\Proposal  $proposal
     * @return mixed
     */
    public function view(User $user, Proposal $proposal)
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
     * @param  \App\Models\Proposal  $proposal
     * @return mixed
     */
    public function update(User $user, Proposal $proposal, $newStatus = null)
    {
        
        // if($newStatus != null && $newStatus != $proposal->status){
        //     return $this->completeDonatingStatus($user, $proposal) || $this->completeExecutionStatus($user, $proposal);
        // }
        if(!in_array($user->type, [1, 2, 3, 5])) return false;
        if($newStatus === 2) return $this->completeDonatingStatus($user, $proposal);
        if($newStatus === 3) return $this->completeExecutionStatus($user, $proposal);

        return true;


    }
    public function completeDonatingStatus(User $user, Proposal $proposal)
    {
        if(!in_array($user->type, [1, 2, 3])) return false;
        if($proposal->status !== 1) return false;

        $pendingDonationsCount = $proposal->pending_donating_count ?? 
        $proposal->donations()->where('status', 0)->count();

        return $pendingDonationsCount === 0;
    }
    public function completeExecutionStatus(User $user, Proposal $proposal)
    {
        // return true;
        if(!in_array($user->type, [1, 5])) return false;
        if($proposal->status != 2 ) return false;

        // Check if the proposal has at least one attachable of type 2
        // $proposalAttachableOfType2 = $proposal->attachments()
        // ->where('attachment_type', 2)
        // ->exists();
        // if(!$proposalAttachableOfType2) return false;

        // // Check if all related documents have at least one attachable record
        // $documentsWithoutAttachable = $proposal->documents()
        //     ->whereDoesntHave('attachments') // Check for documents without attachments
        //     ->exists();

        // if ($documentsWithoutAttachable) {
        //     return false;
        // }

        return true;

    }
    public function completeArchivingStatus(User $user, Proposal $proposal)
    {
        // return true;
        if(!in_array($user->type, [1])) return false;
        if($proposal->status != 3 ) return false;
        return true;

    }
    public function canClone(User $user, Proposal $proposal)
    {
        // return true;
        if(!in_array($user->type, [1, 2, 3])) return false;
        return true;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return mixed
     */
    public function delete(User $user, Proposal $proposal)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return mixed
     */
    public function restore(User $user, Proposal $proposal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Proposal  $proposal
     * @return mixed
     */
    public function forceDelete(User $user, Proposal $proposal)
    {
        //
    }
}
