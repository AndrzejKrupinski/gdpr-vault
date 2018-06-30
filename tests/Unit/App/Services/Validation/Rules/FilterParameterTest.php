<?php

namespace Tests\Unit\App\Services\Validation\Rules;

use App\Services\Validation\Rules\FilterParameter;
use PHPUnit\Framework\TestCase;

class FilterParameterTest extends TestCase
{
    public function test_validates_value()
    {
        $rule = new FilterParameter;

        $result1 = $rule->passes($this->anything(), ['foo' => 'bar']);
        $result2 = $rule->passes($this->anything(), [0 => 'bar']);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }
}
