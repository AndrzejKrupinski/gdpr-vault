<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueryRequest;
use App\Http\Transformers\QueryTransformer;
use Vault\Exceptions\RepositoryException;
use Vault\Filtering\Queries;
use Vault\Filtering\Query;
use WebGarden\Model\ValueObject\Identity\Uuid;

class QueryController extends ResourceController
{
    protected $transformer = QueryTransformer::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QueryRequest  $request
     * @param  \Vault\Filtering\Queries  $queries
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(QueryRequest $request, Queries $queries)
    {
        $id = $request->resourceId() ?: Uuid::generate();
        $query = new Query($id, new \ArrayObject($request->filters()));

        try {
            throw_unless($queries->store($query), $this->exceptionFactory->create());
        } catch (RepositoryException $exception) {
            throw $this->exceptionFactory->create($exception->getMessage());
        }

        return $this->response->item($query, $this->transformer(), $this->responseParameters());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @param  \Vault\Filtering\Queries  $queries
     * @return \Illuminate\Http\Response
     */
    public function show($id, Queries $queries)
    {
        if (!$query = $queries->get(Uuid::fromNative($id))) {
            throw $this->exceptionFactory->createNotFound();
        }

        return $this->response->item($query, $this->transformer(), $this->responseParameters());
    }
}
