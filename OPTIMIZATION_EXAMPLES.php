<?php

/**
 * QUERY OPTIMIZATION QUICK REFERENCE
 * 
 * This file contains examples of how to use the optimized query patterns
 * to avoid N+1 queries in your controllers.
 */

// ============================================================================
// PROPOSALS
// ============================================================================

// List proposals with computed attributes
$proposals = \App\Models\Proposal::query()
    ->with(['entity', 'area', 'proposalType', 'currency'])
    ->withComputedAttributes() // Adds paid_amount, remaining_amount
    ->paginate(15);

return \App\Http\Resources\ProposalListResource::collection($proposals);

// Single proposal with all details
$proposal = \App\Models\Proposal::withComputedAttributes()->findOrFail($id);
$proposal->load(['entity', 'area', 'proposalType', 'currency', 'documents']);

return \App\Http\Resources\ProposalDetailResource::make($proposal);

// Public proposals (for guest view)
$proposals = \App\Models\Proposal::query()
    ->with(['entity', 'area', 'proposalType', 'currency'])
    ->withComputedAttributes()
    ->public()
    ->get();

// ============================================================================
// DONATIONS
// ============================================================================

// List donations with all relationships
$donations = \App\Models\Donation::query()
    ->with(['donor.country', 'payment_method', 'proposal', 'currency'])
    ->paginate(15);

return \App\Http\Resources\DonationListResource::collection($donations);

// Donations for a specific proposal
$donations = $proposal->donations()
    ->with(['donor.country', 'payment_method', 'currency'])
    ->paginate(15);

return \App\Http\Resources\DonationListResource::collection($donations);

// ============================================================================
// DONORS
// ============================================================================

// List donors with country
$donors = \App\Models\Donor::query()
    ->with('country')
    ->paginate(15);

return \App\Http\Resources\DonorListResource::collection($donors);

// ============================================================================
// ENTITIES
// ============================================================================

// List entities (without expensive counts)
$entities = \App\Models\Entity::query()
    ->with(['supervisor', 'country'])
    ->paginate(15);

return \App\Http\Resources\EntityListResource::collection($entities);

// Single entity with counts (only when needed)
$entity = \App\Models\Entity::with(['supervisor', 'country'])->findOrFail($id);

// Manually add counts only for detail view
$entity->completed_projects_count = $entity->initial_completed_projects + 
    $entity->proposals()->whereNotIn('status', [1])->count();
$entity->number_of_public_proposal = $entity->proposals()->public()->count();
$entity->donations_count = $entity->proposals()->withCount('donations')->get()->sum('donations_count');

return \App\Http\Resources\EntityListResource::make($entity);

// ============================================================================
// IMPORTANT PATTERNS
// ============================================================================

// ❌ BAD: This causes N+1 queries
$proposals = \App\Models\Proposal::all();
foreach ($proposals as $proposal) {
    echo $proposal->entity->name; // N+1 query!
}

// ✅ GOOD: Eager load relationships
$proposals = \App\Models\Proposal::with('entity')->get();
foreach ($proposals as $proposal) {
    echo $proposal->entity->name; // No extra query
}

// ❌ BAD: Accessing appended attributes that run queries
$proposals = \App\Models\Proposal::all();
foreach ($proposals as $proposal) {
    echo $proposal->paid_amount; // Runs query for each proposal!
}

// ✅ GOOD: Use database subqueries
$proposals = \App\Models\Proposal::withComputedAttributes()->get();
foreach ($proposals as $proposal) {
    echo $proposal->paid_amount; // Already loaded from database
}

// ❌ BAD: Running authorization gates in model appends
protected $appends = ['can_edit'];
public function getCanEditAttribute() {
    return Gate::allows('edit', $this); // Runs for every model!
}

// ✅ GOOD: Run authorization in resources or controllers
class ProposalResource extends JsonResource {
    public function toArray($request) {
        return [
            'can_edit' => Gate::allows('edit', $this->resource),
        ];
    }
}

// ============================================================================
// DEBUGGING QUERIES
// ============================================================================

// Enable query logging to see all queries
\DB::enableQueryLog();

// Your code here
$proposals = \App\Models\Proposal::with('entity')->get();

// See all queries
dd(\DB::getQueryLog());

// Count queries
echo 'Total queries: ' . count(\DB::getQueryLog());
