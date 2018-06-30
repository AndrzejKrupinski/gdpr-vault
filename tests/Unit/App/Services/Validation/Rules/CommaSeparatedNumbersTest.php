<?php

namespace Tests\Unit\App\Services\Validation\Rules;

use App\Services\Validation\Rules\CommaSeparatedNumbers;
use PHPUnit\Framework\TestCase;

class CommaSeparatedNumbersTest extends TestCase
{
    /** @dataProvider valuesProvider */
    public function test_validates_value($value, $expected)
    {
        $rule = new CommaSeparatedNumbers;

        $actual = $rule->passes($this->anything(), $value);

        $this->assertEquals($expected, $actual);
    }

    public static function valuesProvider()
    {
        return [
            'single value' => [123, true],
            'two values' => ['1,23', true],
            'more values' => ['123,45,6', true],
            'negative' => ['-123', false],
            'decimal' => ['0.25,', false],
            'single letter' => ['a', false],
            'mixed values' => ['123,abc,345', false],
        ];
    }
}
