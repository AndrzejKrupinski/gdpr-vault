<?php

namespace App\Console\Commands;

use App\Models\Consent;
use Illuminate\Console\Command;

class PurgeExpiredConsentsCommand extends Command
{
    /** @var string */
    protected $signature = 'vault:consents:purge-expired';

    /** @var string */
    protected $description = 'Remove expired consents and obfuscate any personal data related to them';

    public function handle()
    {
        $revoked = 0;

        Consent::expired()->each(function (Consent $consent) use (&$revoked) {
            $revoked += (int) $consent->delete();
        });

        $message = $revoked
            ? sprintf('Purged %d %s', $revoked, str_plural('consent', $revoked))
            : 'Nothing to purge';

        $this->info($message);
    }
}
