<?php

namespace App\Providers;

use App\Models\Consent;
use App\Models\Email;
use App\Models\Observers\ConsentObserver;
use App\Models\Observers\IndexedModelObserver;
use App\Models\PersonalDetail;
use App\Models\Phone;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array The observer classes mappings */
    protected $observe = [
        Consent::class => ConsentObserver::class,
        Email::class => IndexedModelObserver::class,
        PersonalDetail::class => IndexedModelObserver::class,
        Phone::class => IndexedModelObserver::class,
    ];

    public function boot()
    {
        parent::boot();

        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->observe as $model => $observer) {
            $model::observe($observer);
        }
    }
}
