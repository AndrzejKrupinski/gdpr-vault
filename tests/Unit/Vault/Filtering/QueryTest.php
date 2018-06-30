<?php

namespace Tests\Unit\Vault\Filtering;

use PHPUnit\Framework\TestCase;
use Vault\Filtering\Query;
use WebGarden\Model\ValueObject\Identity\Uuid;
use WebGarden\Model\ValueObject\Number\Natural;

class QueryTest extends TestCase
{
    public function test_creates_an_entity_using_default_ttl()
    {
        $uuid = $this->createMock(Uuid::class);
        $ttl = Natural::fromNative(3600);

        $query = new Query($uuid, new \ArrayObject);

        $this->assertTrue($query->timeToLive()->sameValueAs($ttl));
    }

    public function test_creates_an_entity_using_custom_ttl()
    {
        $uuid = $this->createMock(Uuid::class);
        $ttl = Natural::fromNative(1234);

        $query = new Query($uuid, new \ArrayObject, $ttl);

        $this->assertTrue($query->timeToLive()->sameValueAs($ttl));
    }
}
