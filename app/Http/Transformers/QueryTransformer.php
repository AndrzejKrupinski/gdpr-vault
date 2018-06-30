<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Vault\Filtering\Query;

class QueryTransformer extends TransformerAbstract
{
    public function transform(Query $query)
    {
        return [
            'id' => $query->id(),
            'filters' => $query->filters()->getArrayCopy(),
            'ttl' => $query->timeToLive()->toNative(),
        ];
    }
}
