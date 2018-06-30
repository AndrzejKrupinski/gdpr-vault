<?php

namespace App\Providers;

use App\Models\User;
use App\Services\LegacyHasher\LegacyHasher;
use Illuminate\Auth\EloquentUserProvider;

class LegacyUserProvider extends EloquentUserProvider
{
    public function __construct(LegacyHasher $hasher)
    {
        parent::__construct($hasher, User::class);
    }
}
