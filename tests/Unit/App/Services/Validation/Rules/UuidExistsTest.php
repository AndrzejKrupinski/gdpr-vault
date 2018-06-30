<?php

namespace Tests\Unit\App\Services\Validation\Rules;

use App\Services\Validation\Rules\UuidExists;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class UuidExistsTest extends TestCase
{
    /** @expectedException \InvalidArgumentException */
    public function test_throws_exception_when_model_does_not_have_binary_uuid()
    {
        new UuidExists(Model::class);
    }
}
