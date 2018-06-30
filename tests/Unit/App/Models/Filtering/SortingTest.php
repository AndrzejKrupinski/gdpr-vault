<?php

namespace Tests\Unit\App\Models\Filtering;

use App\Models\Filtering\Sorting;
use PHPUnit\Framework\TestCase;

class SortingTest extends TestCase
{
    public function test_parses_column_name()
    {
        $sorting = new Sorting('+-foo-bar');

        $this->assertEquals('foo-bar', $sorting->column());
    }

    public function test_parses_sorting_order()
    {
        $sorting1 = new Sorting('foo-bar');
        $sorting2 = new Sorting('-foo-bar');

        $this->assertEquals('asc', $sorting1->order());
        $this->assertEquals('desc', $sorting2->order());
    }

    public function test_checks_sorting_paramerters()
    {
        $sorting1 = new Sorting('foobar');
        $sorting2 = new Sorting('');

        $this->assertTrue($sorting1->valid());
        $this->assertFalse($sorting2->valid());
    }
}
