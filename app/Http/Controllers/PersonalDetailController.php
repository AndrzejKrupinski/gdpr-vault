<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\PersonalDetailRequest as StoreRequest;
use App\Http\Transformers\PersonaldetailTransformer;
use App\Models\Filtering\PersonalDetailFilters;
use App\Models\Person;
use App\Models\PersonalDetail;

class PersonalDetailController extends ResourceController
{
    /** @var string */
    protected $transformer = PersonaldetailTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $personalDetails = PersonalDetail::filterOrFail(new PersonalDetailFilters($request))->jsonPaginate();

        return $this->paginator($personalDetails);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @todo Client-generated id should be used
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $personUuid = $request->resourceRelationships()->oneToOne('person.uuid');

        $personalDetail = Person::withUuid($personUuid)->firstOrFail()
            ->personalDetails()
            ->create($request->resourceAttributes());

        return $this->created($personalDetail);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonalDetail  $personalDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PersonalDetail $personalDetail)
    {
        return $this->item($personalDetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonalDetail  $personalDetail
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(PersonalDetail $personalDetail)
    {
        $personalDetail->delete();

        return $this->response->noContent();
    }
}
