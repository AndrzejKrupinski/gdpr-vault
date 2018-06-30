<?php

namespace Tests\Unit\App\Services\Validation;

use App\Services\Validation\Rule;
use App\Services\Validation\Rules\UuidNotExists;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    public function test_creates_uuid_not_exists_rule_using_resource_name()
    {
        $rule = Rule::resourceNotExists('emails');

        $this->assertInstanceOf(UuidNotExists::class, $rule);
    }
}
