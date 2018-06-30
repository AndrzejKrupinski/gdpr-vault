<?php

namespace Vault\Filtering;

use ArrayObject;
use WebGarden\Model\Entity\Entity;
use WebGarden\Model\ValueObject\Number\Natural;
use WebGarden\Model\ValueObject\ValueObject;

class Query extends Entity
{
    /** @var int Default number of seconds representing the time to live */
    protected const DEFAULT_TTL = 3600;

    /** @var \ArrayObject */
    private $filters;

    /** @var \WebGarden\Model\ValueObject\Number\Natural */
    private $timeToLive;

    public function __construct(ValueObject $id, ArrayObject $filters, Natural $timeToLive = null)
    {
        $this->id = $id;
        $this->filters = $filters;
        $this->timeToLive = $timeToLive ?: Natural::fromNative(self::DEFAULT_TTL);
    }

    public function filters(): ArrayObject
    {
        return $this->filters;
    }

    public function timeToLive(): Natural
    {
        return $this->timeToLive;
    }
}
