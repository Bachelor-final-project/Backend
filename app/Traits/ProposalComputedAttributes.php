<?php

namespace App\Traits;

trait ProposalComputedAttributes
{
    public function scopeWithPaidAmount($query)
    {
        return $query->selectRaw('proposals.*, COALESCE((SELECT SUM(amount) FROM donations WHERE donations.proposal_id = proposals.id AND donations.status = 2), 0) as paid_amount');
    }

    public function scopeWithRemainingAmount($query)
    {
        return $query->selectRaw('proposals.*, (proposals.cost - COALESCE((SELECT SUM(amount) FROM donations WHERE donations.proposal_id = proposals.id AND donations.status = 2), 0)) as remaining_amount');
    }

    public function scopeWithCompleteDonatingStatusDate($query)
    {
        return $query->selectRaw('proposals.*, DATE_FORMAT((SELECT created_at FROM logs WHERE logs.loggable_id = proposals.id AND logs.loggable_type = ? AND logs.log_type = 1 ORDER BY created_at ASC LIMIT 1), "%Y-%m-%d") as complete_donating_status_date', ['App\\Models\\Proposal']);
    }

    public function scopeWithCoverImage($query)
    {
        return $query->selectRaw('proposals.*, (SELECT url FROM attachments WHERE attachments.attachable_id = proposals.id AND attachments.attachable_type = ? AND attachments.attachment_type = 1 ORDER BY id ASC LIMIT 1) as cover_image', ['App\\Models\\Proposal']);
    }

    public function scopeWithPendingDonatingCount($query)
    {
        return $query->selectRaw('proposals.*, (SELECT COUNT(*) FROM donations WHERE donations.proposal_id = proposals.id AND donations.status = 0) as pending_donating_count');
    }

    public function scopeWithComputedAttributes($query)
    {
        return $query->selectRaw('
            proposals.*,
            COALESCE((SELECT SUM(amount) FROM donations WHERE donations.proposal_id = proposals.id AND donations.status = 2), 0) as paid_amount,
            (proposals.cost - COALESCE((SELECT SUM(amount) FROM donations WHERE donations.proposal_id = proposals.id AND donations.status = 2), 0)) as remaining_amount
        ');
    }
}
