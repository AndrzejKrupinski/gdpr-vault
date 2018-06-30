<?php

namespace App\Models\Filtering;

use App\Models\Purpose;
use App\Models\Scopes\AssociatedWithPersonScope;

class ConsentFilters extends QueryFilters
{
    protected $sortable = ['expired_at'];

    protected $validationRules = [
        'confirmed' => 'boolean',
        'person' => self::UUID_VALIDATION_RULES,
        'purpose' => self::UUID_VALIDATION_RULES,
    ];

    /**
     * Filter by the confirmation state.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterConfirmed($query, FilterParameters $filter)
    {
        return $query->where('confirmed', $filter->value());
    }

    /**
     * Filter by email addresses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterEmailsAddress($query, FilterParameters $filter)
    {
        return $query->whereHas('emails', function ($query) use ($filter) {
            (new EmailFilters)->filterAddress($query, $filter);
        });
    }

    /**
     * Filter by the given person.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterPerson($query, FilterParameters $filter)
    {
        return $query->replaceGlobalScope(
            new AssociatedWithPersonScope($filter->value())
        );
    }

    /**
     * Filter by phone numbers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterPhonesNumber($query, FilterParameters $filter)
    {
        return $query->whereHas('phones', function ($query) use ($filter) {
            (new PhoneFilters)->filterNumber($query, $filter);
        });
    }

    /**
     * Filter by the given purpose.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterPurpose($query, FilterParameters $filter)
    {
        return $query->whereHas('purposes', function ($query) use ($filter) {
            $query->where((new Purpose)->getQualifiedKeyName(), Purpose::encodeUuid($filter->value()));
        });
    }
}
