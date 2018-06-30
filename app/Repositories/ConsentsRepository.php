<?php

namespace App\Repositories;

use App\Exceptions\Factory\ResourceExceptionFactory;
use App\Http\Requests\JsonApiRequest;
use App\Models\Consent;

class ConsentsRepository extends ResourceRepository
{
    /** @var ResourceExceptionFactory */
    protected $exceptionFactory;

    /** @var string */
    protected $model = Consent::class;

    /** @var \App\Http\Requests\RelationshipsCollection */
    protected $relationships;

    public function __construct(JsonApiRequest $request)
    {
        parent::__construct($request);

        $this->exceptionFactory = new ResourceExceptionFactory($request);
        $this->relationships = $request->resourceRelationships();
    }

    public function store($model)
    {
        if (! $this->validateContactables($model)) {
            throw $this->exceptionFactory->create(
                trans('validation.relationships.invalid_contactables')
            );
        }

        return parent::store($model);
    }

    /**
     * Check whether contactable relationships belongs to the given person.
     *
     * @param  \App\Models\Model  $model
     * @return bool
     */
    protected function validateContactables($model)
    {
        if ($this->relationships->contactable()->isEmpty()) {
            return true;
        }

        $personUuid = $this->relationships->oneToOne('person.uuid')
            ?: $model->getAttribute('person_id');

        $contactables = $this->relationships->contactable()
            ->flatten(1)
            ->pluck('uuid')
            ->transform(function ($uuid) {
                return Consent::encodeUuid($uuid);
            });

        $found = app('db')->query()
            ->from('contactables')
            ->where('person_id', Consent::encodeUuid($personUuid))
            ->whereIn('contactable_id', $contactables)
            ->count('person_id');

        return $found === $contactables->count();
    }
}
