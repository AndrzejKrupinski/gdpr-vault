<?php

namespace spec\App\Jobs;

use PhpSpec\ObjectBehavior;

class ReindexIndexesSpec extends ObjectBehavior
{
    const SPHINXSEARCH_CONFIG = [
        'config_path' => 'sphinx.conf',
        'indexer_path' => 'indexer'
    ];

    function it_prepares_default_command()
    {
        $this->beConstructedWith(null, self::SPHINXSEARCH_CONFIG);

        $this->getCommand()->shouldBe('indexer --config sphinx.conf --rotate --all');
    }

    function it_prepares_command_using_indexes_array()
    {
        $this->beConstructedWith(['foo', 'bar'], self::SPHINXSEARCH_CONFIG);

        $this->getCommand()->shouldBe('indexer --config sphinx.conf --rotate foo bar');
    }

    function it_prepares_command_using_indexes_string()
    {
        $this->beConstructedWith('foo,bar', self::SPHINXSEARCH_CONFIG);

        $this->getCommand()->shouldBe('indexer --config sphinx.conf --rotate foo bar');
    }
}
