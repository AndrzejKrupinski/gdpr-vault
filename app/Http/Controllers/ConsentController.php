<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\ConsentRequest as StoreRequest;
use App\Http\Transformers\ConsentTransformer;
use App\Models\Consent;
use App\Models\Filtering\ConsentFilters;
use App\Repositories\ConsentsRepository;

class ConsentController extends ResourceController
{
    /** @var string */
    protected $transformer = ConsentTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $consents = Consent::filterOrFail(new ConsentFilters($request))->jsonPaginate();

        return $this->paginator($consents);
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
        $consent = ConsentsRepository::usingRequest($request)->create();

        return $this->created($consent);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consent  $consent
     * @return \Illuminate\Http\Response
     */
    public function show(Consent $consent)
    {
        return $this->item($consent);
    }

    /**
     * Update the specified resource.
     *
     * @param  \App\Models\Consent  $consent
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Consent $consent, StoreRequest $request)
    {
        ConsentsRepository::usingRequest($request)->store($consent);

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consent  $consent
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Consent $consent)
    {
        $consent->delete();

        return $this->response->noContent();
    }
}
