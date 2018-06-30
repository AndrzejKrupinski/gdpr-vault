<?php

namespace Tests\Unit\App\Services\Validation\Rules;

use App\Services\Validation\Rules\ResourceRelationship;
use Tests\TestCase;

class ResourceRelationshipTest extends TestCase
{
    public function test_validates_value_for_given_resource_types()
    {
        $rule = new ResourceRelationship(['people', 'emails']);

        $result1 = $rule->passes($this->anything(), [['type' => 'people'], ['type' => 'emails']]);
        $result2 = $rule->passes($this->anything(), ['type' => 'dummy_resource_type']);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    public function test_validates_multidimensional_arrays()
    {
        $rule = new ResourceRelationship('people');

        $result1 = $rule->passes($this->anything(), ['person' => ['data' => ['type' => 'people']]]);
        $result2 = $rule->passes($this->anything(), ['person' => ['data' => ['type' => 'dummy_resource_type']]]);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    public function test_validation_fails_when_no_resource_types_given()
    {
        $rule = new ResourceRelationship([]);

        $result = $rule->passes($this->anything(), ['type' => 'any_resource_type']);

        $this->assertFalse($result);
    }
}
