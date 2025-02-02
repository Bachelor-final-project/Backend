<?php

namespace App\Events;

use App\Models\Proposal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposalDonatingStatusApprovedWithDonatedAmount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proposal;
    public $donatingAmount;
    public $recipient_id;
    public $receipts;
    /**
     * Create a new event instance.
     */
    public function __construct(Proposal $proposal, int $donatingAmount, $recipient_id, $receipts)
    {
        $this->proposal = $proposal;
        $this->donatingAmount = $donatingAmount;
        $this->recipient_id = $recipient_id;
        $this->receipts = $receipts;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
