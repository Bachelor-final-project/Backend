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
        $donatino = new Donation();
        $donatino->proposal_id = $event->proposal->id;
        $donatino->currency_id = $event->proposal->currency_id;
        $donatino->amount = $event->donatingAmount;
        $donatino->status = 2; //approved

        $donatino->save();
        
    }
}
