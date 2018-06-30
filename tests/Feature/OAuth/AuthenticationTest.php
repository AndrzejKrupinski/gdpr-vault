<?php

namespace Tests\Feature\OAuth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /** @var  Client */
    protected $client;

    /** @var  User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->client = with(new ClientRepository, function ($repository) {
            return $repository->createPasswordGrantClient(1, '', '');
        });

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function client_credentials_grant()
    {
        $parameters = [
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'grant_type' => 'client_credentials',
            'scope' => '*',
        ];

        $this->assertValidGrantResponse($parameters);
    }

    /** @test */
    public function password_grant()
    {
        $parameters = [
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'grant_type' => 'password',
            'username' => $this->user->UserLogin,
            'password' => 'password',
            'scope' => '*',
        ];

        $this->assertValidGrantResponse($parameters);
    }

    protected function assertValidGrantResponse(array $requestParams)
    {
        $response_keys = [
            'token_type',
            'expires_in',
            'access_token',
        ];

        $this->post('oauth/token', $requestParams)->assertJsonStructure($response_keys);
    }
}
