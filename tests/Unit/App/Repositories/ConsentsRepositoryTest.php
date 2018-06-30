<?php

namespace Tests\Unit\App\Repositories;

use App\Http\Requests\JsonApiRequest;
use App\Models\Consent;
use App\Repositories\ConsentsRepository;
use Tests\TestCase;

class ConsentsRepositoryTest extends TestCase
{
    public function test_named_constructor()
    {
        $dummyRequest = $this->createMock(JsonApiRequest::class);

        $repository = ConsentsRepository::usingRequest($dummyRequest);

        $this->assertInstanceOf(ConsentsRepository::class, $repository);
    }

    public function test_creates_a_new_model_with_a_proper_id()
    {
        $requestStub = $this->createMock(JsonApiRequest::class);
        $requestStub->expects($this->once())->method('resourceId')->willReturn(1);
        $repository = new ConsentsRepository($requestStub);

        $subject = $repository->newModelInstance();

        $this->assertInstanceOf(Consent::class, $subject);
        $this->assertEquals(1, $subject->id);
    }

    public function test_stores_a_newly_created_model()
    {
        $repositoryStub = $this->getMockBuilder(ConsentsRepository::class)
            ->setConstructorArgs([$this->createMock(JsonApiRequest::class)])
            ->setMethods(['newModelInstance', 'store', 'validateContactables'])
            ->getMock();
        $repositoryStub->method('validateContactables')->willReturn(true);
        $repositoryStub->expects($newModelInstanceSpy = $this->once())->method('newModelInstance');
        $repositoryStub->expects($storeSpy = $this->once())->method('store');

        $repositoryStub->create();

        $this->assertEquals(1, $newModelInstanceSpy->getInvocationCount());
        $this->assertEquals(1, $storeSpy->getInvocationCount());

    }
}
