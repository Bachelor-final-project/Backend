<?php

namespace App\Listeners;

use App\Events\ProposalDonatingStatusApprovedWithDonatedAmount;
use App\Models\Donation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDonationRecord
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProposalDonatingStatusApprovedWithDonatedAmount $event): void
    {
        // create donation
        
    }
}
