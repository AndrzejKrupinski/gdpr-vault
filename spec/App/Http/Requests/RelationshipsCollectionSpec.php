<?php

namespace spec\App\Http\Requests;

use App\Http\Requests\RelationshipsCollection;
use App\Http\Requests\JsonApiRequest;
use PhpSpec\ObjectBehavior;

class RelationshipsCollectionSpec extends ObjectBehavior
{
    function let(JsonApiRequest $request)
    {
        $payload = include __DIR__ . '/fixtures/jsonApiPayload.php';

        $request->input('data.relationships')->willReturn($payload['data']['relationships']);

        $this->beConstructedWith($request);
    }

    function it_creates_collection_using_json_api_request($request)
    {
        $this->shouldBeAnInstanceOf(RelationshipsCollection::class);

        $request->input('data.relationships')->shouldHaveBeenCalled();
    }

    function it_normalizes_relationships()
    {
        $this->oneToOne()->toArray()->shouldBe([
            ['key' => 'person_id', 'type' => 'people', 'uuid' => 'b9ea3b5d-eb2a-4005-b14a-9a573090bfab'],
            ['key' => 'content_id', 'type' => 'contents', 'uuid' => 'b9a2a1c2-5b40-4004-8094-f4a897da0a7a'],
        ]);
        $this->oneToMany()->toArray()->shouldHaveKeyWithValue('purposes', [
            ['key' => 'purpose_id', 'type' => 'purposes', 'uuid' => 'e32af7e9-70ee-404a-a1f1-b22d3b93bef3'],
        ]);
    }

    function it_gets_one_to_one_relationship_by_its_name()
    {
        $this->oneToOne('person')->shouldBe([
            'key' => 'person_id',
            'type' => 'people',
            'uuid' => 'b9ea3b5d-eb2a-4005-b14a-9a573090bfab',
        ]);
        $this->oneToOne('person.key')->shouldBe('person_id');
        $this->oneToOne('person.type')->shouldBe('people');
        $this->oneToOne('person.uuid')->shouldBe('b9ea3b5d-eb2a-4005-b14a-9a573090bfab');
    }
}
