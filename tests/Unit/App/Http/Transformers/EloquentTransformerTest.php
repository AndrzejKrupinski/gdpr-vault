<?php

namespace Tests\Unit\App\Http\Transformers;

use App\Http\Transformers\EloquentTransformer;
use PHPUnit\Framework\TestCase;

class EloquentTransformerTest extends TestCase
{
    /** @var EloquentTransformer */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new EloquentTransformer;
    }

    public function test_retrieves_eloquent_model_attributes()
    {
        $model = $this->createMock('App\Models\Model');
        $model->method('attributesToArray')->willReturn(['foo' => 1]);

        $attributes = $this->transformer->transform($model);

        $this->assertEquals(['foo' => 1], $attributes);
    }

    public function test_excludes_hidden_attributes()
    {
        $model = $this->createMock('App\Models\Model');
        $model->method('attributesToArray')->willReturn(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $this->transformer->setHiddenAttributes(['bar', 'baz']);
        $attributes = $this->transformer->transform($model);

        $this->assertArrayHasKey('foo', $attributes);
        $this->assertArrayNotHasKey('bar', $attributes);
        $this->assertArrayNotHasKey('baz', $attributes);
    }

    public function test_appends_hidden_attributes()
    {
        $this->transformer->setHiddenAttributes(['foo', 'bar']);

        $this->transformer->appendHiddenAttributes(['baz', 'qux']);

        $this->assertEquals(['foo', 'bar', 'baz', 'qux'], $this->transformer->hiddenAttributes());
    }

    public function test_gets_relation_value()
    {
        $model = $this->createMock('App\Models\Model');
        $model->expects($spy = $this->any())->method('getRelationValue');
        $parameters = $this->createMock('League\Fractal\ParamBag');

        $this->transformer->includeFoobar($model, $parameters);

        $this->assertEquals(1, $spy->getInvocationCount());
    }

    public function test_returns_item_for_eloquent_model()
    {
        $model = $this->createMock('App\Models\Model');
        $model->method('getRelationValue')->willReturn($model);
        $parameters = $this->createMock('League\Fractal\ParamBag');

        $actual = $this->transformer->includeFoobar($model, $parameters);

        $this->assertInstanceOf('League\Fractal\Resource\Item', $actual);
    }

    public function test_returns_collection_for_eloquent_collection()
    {
        $collection = $this->createMock('Illuminate\Database\Eloquent\Collection');
        $model = $this->createMock('App\Models\Model');
        $model->method('getRelationValue')->willReturn($collection);
        $parameters = $this->createMock('League\Fractal\ParamBag');

        $actual = $this->transformer->includeFoobar($model, $parameters);

        $this->assertInstanceOf('League\Fractal\Resource\Collection', $actual);
    }
}
