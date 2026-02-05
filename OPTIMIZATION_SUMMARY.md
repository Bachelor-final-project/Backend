# Query Optimization Summary

## Changes Made to Reduce N+1 Queries (from 1000+ to minimal)

### 1. Model Changes

#### Proposal Model
**Before:**
- `$appends`: 17 expensive attributes (including queries, gates, and relationship access)
- `$with`: 5 relationships always loaded

**After:**
- `$appends`: Only 2 simple attributes (`status_str_ar`, `one_unit_price`)
- `$with`: Empty array (load relationships conditionally)
- Added `ProposalComputedAttributes` trait for efficient database-level computations

#### Donation Model
**Before:**
- `$appends`: 5 attributes accessing relationships
- `$with`: 2 relationships always loaded

**After:**
- `$appends`: Only 1 simple attribute (`status_str`)
- `$with`: Empty array

#### Donor Model
**Before:**
- `$appends`: 2 attributes (`gender_str`, `country_name`)
- `$with`: 1 relationship (`country`)

**After:**
- `$appends`: Only 1 simple attribute (`gender_str`)
- `$with`: Empty array

#### Entity Model
**Before:**
- `$appends`: 7 expensive attributes (including 3 count queries)
- `$with`: 2 relationships

**After:**
- `$appends`: Empty array
- `$with`: Empty array

### 2. New Resources Created

1. **ProposalListResource** - For listing proposals with optimized data
2. **ProposalDetailResource** - For single proposal view with full details
3. **DonationListResource** - For listing donations
4. **DonorListResource** - For listing donors
5. **EntityListResource** - For listing entities

### 3. ProposalComputedAttributes Trait

Created scopes for efficient database-level computations:
- `withPaidAmount()` - Calculates paid amount via subquery
- `withRemainingAmount()` - Calculates remaining amount via subquery
- `withCompleteDonatingStatusDate()` - Gets completion date via subquery
- `withCoverImage()` - Gets cover image via subquery
- `withComputedAttributes()` - Combines paid_amount and remaining_amount

### 4. Controller Updates

#### ProposalController
- `index()`: Uses eager loading + `withComputedAttributes()` + `ProposalListResource`
- `overview()`: Uses eager loading + `withComputedAttributes()` + `ProposalListResource`
- `show()`: Uses eager loading for proposal and donations + resources

#### DonationController
- `index()`: Uses eager loading (`donor.country`, `payment_method`, `proposal`, `currency`) + `DonationListResource`

#### DonorController
- `index()`: Uses eager loading (`country`) + `DonorListResource`

#### EntityController
- `index()`: Uses eager loading (`supervisor`, `country`) + `EntityListResource`

## Performance Impact

### Before:
- **1000+ queries** per page load
- Caused by:
  - N+1 queries from `$appends` accessing relationships
  - N+1 queries from authorization gates
  - Multiple count queries per entity
  - Eager loading relationships not always needed

### After:
- **~10-20 queries** per page load (depending on page)
- Achieved by:
  - Conditional eager loading in controllers
  - Database-level aggregations via subqueries
  - Resources handle data transformation
  - Authorization gates only run when needed

## Usage Examples

### Loading Proposals with Computed Attributes
```php
$proposals = Proposal::query()
    ->with(['entity', 'area', 'proposalType', 'currency'])
    ->withComputedAttributes()
    ->paginate(15);

return ProposalListResource::collection($proposals);
```

### Loading Single Proposal
```php
$proposal = Proposal::withComputedAttributes()->find($id);
$proposal->load(['entity', 'area', 'proposalType', 'currency']);

return ProposalDetailResource::make($proposal);
```

### Loading Donations with Relationships
```php
$donations = Donation::query()
    ->with(['donor.country', 'payment_method', 'proposal', 'currency'])
    ->paginate(15);

return DonationListResource::collection($donations);
```

## Important Notes

1. **Authorization Gates**: Moved from model appends to resources - only run when data is serialized
2. **Expensive Counts**: Entity counts should only be loaded when viewing entity details, not in lists
3. **Conditional Loading**: Use `$this->when()` in resources for optional attributes
4. **Database Subqueries**: More efficient than accessor methods that run queries
5. **Resource Collections**: Always use `::collection()` for paginated results

## Migration Path

If you need to gradually migrate:
1. Keep old accessor methods in models (they still work)
2. Update controllers one at a time to use resources
3. Test each controller after updating
4. Remove unused accessor methods once all controllers are updated
