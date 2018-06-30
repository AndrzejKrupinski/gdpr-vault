<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property string $consent_id
 * @property string $purpose_id
 */
class ConsentPurpose extends Pivot
{
}
