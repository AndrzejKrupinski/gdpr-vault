<?php

namespace App\Http\Controllers;

use App\Http\Transformers\EloquentTransformer;
use App\Models\Model;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\Factory\ResourceExceptionFactory as ExceptionFactory;
use Illuminate\Support\Collection;

abstract class ResourceController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, Helpers, ValidatesRequests;

    /** @var \App\Exceptions\Factory\ResourceExceptionFactory */
    protected $exceptionFactory;

    /** @var string */
    protected $transformer = EloquentTransformer::class;

    public function __construct(ExceptionFactory $exceptionFactory)
    {
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * Respond with a created response and associate a location.
     *
     * @param  \App\Models\Model  $model
     * @return \Dingo\Api\Http\Response
     */
    protected function created(Model $model)
    {
        $location = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->resource(
            $this->resourceName(),
            $model->uuid_text
        );

        return $this->response->created($location, $this->item($model));
    }

    /**
     * Determine default resource name based on the controller's class name.
     *
     * @return string|null
     */
    protected function defaultResourceName()
    {
        try {
            $className = (new \ReflectionClass(static::class))->getShortName();

            return str_plural(strtolower(str_replace('Controller', '', $className)));
        } catch (\ReflectionException $exception) {
            // ignore this exception assuming that this is not a resource controller
        }
    }

    /**
     * Return resource name.
     *
     * @return string|null
     */
    protected function resourceName()
    {
        return defined('static::RESOURCE') ? constant('static::RESOURCE') : $this->defaultResourceName();
    }

    /**
     * Get default response parameters.
     *
     * @return array
     */
    protected function responseParameters()
    {
        return [
            'key' => $this->resourceName(),
        ];
    }

    /**
     * Bind a collection to a transformer and start building a response.
     *
     * @param  Collection  $collection
     * @return \Dingo\Api\Http\Response
     */
    protected function collection(Collection $collection)
    {
        return $this->response->collection($collection, $this->transformer(), $this->responseParameters());
    }

    /**
     * Bind an item to a transformer and start building a response.
     *
     * @param  Model  $item
     * @return \Dingo\Api\Http\Response
     */
    protected function item(Model $item)
    {
        return $this->response->item($item, $this->transformer(), $this->responseParameters());
    }

    /**
     * Bind a paginator to a transformer and start building a response.
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator  $paginator
     * @return \Dingo\Api\Http\Response
     */
    protected function paginator(Paginator $paginator)
    {
        return $this->response->paginator($paginator, $this->transformer(), $this->responseParameters());
    }

    /**
     * Create a new instance of the transformer.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function transformer()
    {
        return new $this->transformer;
    }
}
