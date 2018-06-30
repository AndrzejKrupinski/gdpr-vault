<?php

namespace App\Models\Observers;

use App\Models\Consent;
use App\Services\DataObfuscator\DataObfuscator;
use App\Services\DataObfuscator\Rules\LeaveIntact as ObfuscateRule;
use Dingo\Api\Exception\UpdateResourceFailedException;

class ConsentObserver
{
    public function deleted(Consent $consent)
    {
        $this->purgePersonalData($consent);
    }

    /**
     * @param  Consent  $consent
     * @throws UpdateResourceFailedException
     * @throws \Throwable
     */
    public function updating(Consent $consent)
    {
        if ($consent->getOriginal('confirmed')) {
            throw new UpdateResourceFailedException('Consent cannot be updated once it is confirmed.');
        }
    }

    /**
     * Obfuscate personal data related to the given consent.
     *
     * @param  Consent  $consent
     */
    protected function purgePersonalData(Consent $consent)
    {
        $relations = ['person.emails', 'person.phones', 'person.addresses'];

        DataObfuscator::obfuscate($consent, $relations, new ObfuscateRule);
    }
}
