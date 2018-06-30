<?php

namespace spec\Vault\Filtering;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vault\Filtering\Query;
use WebGarden\Model\ValueObject\Identity\Uuid;

class QueriesSpec extends ObjectBehavior
{
    function let($client, $queries)
    {
        $client->beADoubleOf('Predis\ClientInterface');
        $queries->beADoubleOf('Vault\Filtering\Queries');
    }

    function it_returns_existing_query_identified_by_the_given_id($client, $queries, Uuid $id)
    {
        $client->ttl(Argument::any())->willReturn(3600);
        $client->get(Argument::any())->willReturn('a:0:{}');
        $this->beConstructedWith($client, $queries);

        $this->get($id)->shouldBeAnInstanceOf(Query::class);
    }

    function it_returns_null_when_query_does_not_exist($client, $queries, Uuid $id)
    {
        $client->ttl(Argument::any())->willReturn(-1);
        $this->beConstructedWith($client, $queries);

        $this->get($id)->shouldBeNull();
    }

    function it_should_throw_an_exception_when_getting_all_queries($client, $queries)
    {
        $this->beConstructedWith($client, $queries);

        $this->shouldThrow(\BadMethodCallException::class)->during('getAll');
    }
}
