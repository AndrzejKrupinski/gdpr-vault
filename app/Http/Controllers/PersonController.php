<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\PersonRequest as StoreRequest;
use App\Http\Transformers\PersonTransformer;
use App\Models\Filtering\PersonFilters;
use App\Models\Person;

class PersonController extends ResourceController
{
    /** @var string */
    protected $transformer = PersonTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $people = Person::filterOrFail(new PersonFilters($request))->jsonPaginate();

        return $this->paginator($people);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(StoreRequest $request)
    {
        $person = new Person($request->resourceAttributes());
        $person->id = $request->resourceId();
        $person->saveOrFail();

        return $this->created($person);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return $this->item($person);
    }
}
