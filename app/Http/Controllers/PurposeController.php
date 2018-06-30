<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\PurposeRequest as StoreRequest;
use App\Http\Transformers\PurposeTransformer;
use App\Models\Filtering\PurposeFilters;
use App\Models\Purpose;

class PurposeController extends ResourceController
{
    /** @var string */
    protected $transformer = PurposeTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $purposes = Purpose::filterOrFail(new PurposeFilters($request))->jsonPaginate();

        return $this->paginator($purposes);
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
        $purpose = new Purpose($request->resourceAttributes());
        $purpose->id = $request->resourceId();
        $purpose->saveOrFail();

        return $this->created($purpose);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function show(Purpose $purpose)
    {
        return $this->item($purpose);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreRequest  $request
     * @param  \App\Models\Purpose  $purpose
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(StoreRequest $request, Purpose $purpose)
    {
        $purpose->fill($request->resourceAttributes())->saveOrFail();

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purpose  $purpose
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Purpose $purpose)
    {
        $purpose->delete();

        return $this->response->noContent();
    }
}
