<?php

namespace App\Repositories;

use App\Http\Requests\JsonApiRequest;

abstract class ResourceRepository
{
    /** @var string */
    protected $model;

    /** @var JsonApiRequest */
    protected $request;

    /**
     * Return a new repository instance.
     *
     * @param  JsonApiRequest  $request
     * @return static
     */
    public static function usingRequest(JsonApiRequest $request)
    {
        return new static($request);
    }

    /**
     * Create a new repository instance.
     *
     * @param  JsonApiRequest  $request
     */
    public function __construct(JsonApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new model and sync intermediate tables.
     *
     * @return \App\Models\Model
     * @throws \Throwable
     */
    public function create()
    {
        return $this->store($this->newModelInstance());
    }

    /**
     * Return a new model instance.
     *
     * @return \App\Models\Model
     */
    public function newModelInstance()
    {
        $model = new $this->model;
        $model->id = $this->request->resourceId();

        return $model;
    }

    /**
     * Save the model to the repository.
     *
     * @param  \App\Models\Model  $model
     * @return \App\Models\Model
     * @throws \Throwable
     */
    public function store($model)
    {
        $model->fill($this->request->resourceAttributes());

        $this->request->resourceRelationships()
            ->oneToOne()
            ->each(function ($relationship) use ($model) {
                $model->setAttribute($relationship['key'], $relationship['uuid']);
            });

        $model->getConnection()->transaction(function () use ($model) {
            $model->saveOrFail();
            $this->syncRelationships($model);
        });

        return $model;
    }

    /**
     * Synchronize one-to-many relationships.
     *
     * @param  \App\Models\Model  $model
     * @return void
     */
    protected function syncRelationships($model)
    {
        $sync = function ($data, $name) use ($model) {
            $encodedUuids = array_map(function ($relationship) use ($model) {
                return $model::encodeUuid($relationship['uuid']);
            }, $data);

            $model->$name()->sync($encodedUuids);
        };

        $this->request->resourceRelationships()->oneToMany()->each($sync);
    }
}
