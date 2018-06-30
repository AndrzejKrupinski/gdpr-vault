<?php

namespace Vault\Filtering;

use Vault\Exceptions\RepositoryException;
use WebGarden\Model\Entity\Identifiable;
use WebGarden\Model\Repository\Repository;
use WebGarden\Model\ValueObject\Number\Natural;
use WebGarden\Model\ValueObject\ValueObject;

class Queries implements Repository
{
    /** @var \Predis\ClientInterface */
    protected $client;

    /**
     * @param  \Predis\ClientInterface  $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Return a Query identified by the given ValueObject.
     *
     * @param  \WebGarden\Model\ValueObject\ValueObject  $identity
     * @return \Vault\Filtering\Query|null
     */
    public function get(ValueObject $identity)
    {
        if (!$this->exists($identity)) {
            return null;
        }

        $id = $identity->toNative();
        $filters = new \ArrayObject(unserialize($this->client->get($id)));
        $timeToLive = Natural::fromNative($this->client->ttl($id));

        return new Query($identity, $filters, $timeToLive);
    }

    /**
     * @inheritdoc
     * @throws \BadMethodCallException
     */
    public function getAll(): array
    {
        throw new \BadMethodCallException('Method not implemented.');
    }

    /**
     * Determine if the Query exists.
     *
     * @param  \WebGarden\Model\ValueObject\ValueObject  $identity
     * @return bool
     */
    public function exists(ValueObject $identity)
    {
        return $this->client->ttl($identity->toNative()) > 0;
    }

    /**
     * Store the given Query.
     *
     * @param  \Vault\Filtering\Query|Identifiable  $entity
     * @return bool
     */
    public function store(Identifiable $entity)
    {
        try {
            $status = $this->client->setex(
                $entity->id()->toNative(),
                $entity->timeToLive()->toNative(),
                serialize($entity->filters()->getArrayCopy())
            );
        } catch (\Exception $exception) {
            throw new RepositoryException('The given Query entity could not be stored.', $exception);
        }

        return $status == 'OK';
    }
}
