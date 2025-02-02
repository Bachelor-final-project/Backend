<?php

namespace App\Listeners;

use App\Events\ProposalDonatingStatusApprovedWithDonatedAmount;
use App\Models\Donation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Attachment;

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
        $donatino = Donation::create([
            'proposal_id' => $event->proposal->id,
            'currency_id' => $event->proposal->currency_id,
            'amount' => $event->donatingAmount,
            'recipient_id' => $event->recipient_id,
            'status' => 2, //approved
        ]);
        
        Attachment::storeAttachment($event->receipts, $donatino->id, 'donation', 1);
       
    }
}
