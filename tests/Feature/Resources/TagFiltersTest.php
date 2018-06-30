<?php

namespace Tests\Feature\Resources;

use App\Models\Consent;
use App\Models\Content;
use App\Models\Person;
use App\Models\Purpose;
use Spatie\Tags\Tag;

class TagFiltersTest extends ApiTestCase
{
    /**
     * @test
     * @dataProvider endpointsProvider
     */
    public function tag_is_filterable_on_resource_endpoints($endpoint, $model)
    {
        $tag = str_random();
        $resource = factory($model)->create();
        $resource->attachTag($tag);

        $response = $this->get("/$endpoint?filter[tag]=$tag");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $resource->uuid_text])
            ->assertJsonFragment(['count' => 1]);
    }

    /**
     * @test
     */
    public function tag_is_writeable_during_create_people_resource()
    {
        $label = str_random();

        $body = [
            'data' => [
                'type' => 'people',
                'attributes' => [
                    'tags' => [$label],
                    'site' => 44,
                ],
            ],
        ];

        $response = $this->postJson('people', $body, $this->defaultHeaders);

        $response->assertStatus(201);
        $this->assertTrue(Tag::findFromString($label)->exists());
    }


    /**
     * @test
     */
    public function tag_is_writeable_during_create_email_resource()
    {
        $label = str_random();
        $person = factory(Person::class)->create();

        $body = [
            'data' => [
                'type' => 'emails',
                'attributes' => [
                    'tags' => [$label],
                    'address' => 'foobar@foobar.com',
                ],
                'relationships' => [
                    'person' => [
                        'data' => [
                            'id' => $person->uuid_text,
                            'type' => 'people'
                        ]
                    ]
                ]
            ],
        ];

        $response = $this->postJson('emails', $body, $this->defaultHeaders);
        $response->assertStatus(201);
        $this->assertTrue(Tag::findFromString($label)->exists());
    }

    public function endpointsProvider()
    {
        return [
            ['consents', Consent::class],
            ['people', Person::class],
            ['contents', Content::class],
            ['purposes', Purpose::class],
        ];
    }
}
