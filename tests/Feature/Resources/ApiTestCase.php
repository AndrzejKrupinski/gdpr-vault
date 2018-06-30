<?php

namespace Tests\Feature\Resources;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;
use Tests\TestCase;

class ApiTestCase extends TestCase
{
    use DatabaseTransactions;

    protected $connectionsToTransact = ['mysql', 'users'];

    protected $headers = [];

    protected $scopes = [];

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->states('admin')->create();

        $client = with(new ClientRepository, function(ClientRepository $repository) {
            return $repository->createPersonalAccessClient($this->user->id, 'test client', '');
        });

        PersonalAccessClient::create([
            'client_id' => $client->id
        ]);

        $token = $this->user->createToken('test token', $this->scopes)->accessToken;

        $this->defaultHeaders['Authorization'] = "Bearer $token";
        $this->defaultHeaders['Content-Type'] = sprintf('application/%s.api+json', env('API_STANDARDS_TREE'));
        $this->defaultHeaders['Accept'] = sprintf('application/%s.api+json', env('API_STANDARDS_TREE'));
    }
}
