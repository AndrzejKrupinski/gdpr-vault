<?php

namespace App\Http\Middleware\JsonApi\ValidationPipes;

use App\Exceptions\ResourceAlreadyExistsException;
use App\Services\Validation\Rule;
use Illuminate\Http\Request;

/**
 * @todo The Queries resource should be included as well.
 */
class PostPayload extends CommonPayload
{
    protected $defaultException = ResourceAlreadyExistsException::class;

    /** @var array List of resources that should be excluded. */
    protected $excludeResources = [
        'queries'
    ];

    public function rules(Request $request)
    {
        if ($this->shouldBeExcluded($request->resourceName())) {
            return [];
        }

        return [
            'data.id' => Rule::resourceNotExists(
                $request->resourceName(),
                $request->resourceId()
            ),
        ];
    }

    /**
     * Determine if the given resource should be excluded.
     *
     * @param  string  $resourceName
     * @return bool
     */
    protected function shouldBeExcluded($resourceName)
    {
        return in_array($resourceName, $this->excludeResources);
    }
}
