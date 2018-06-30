<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\PhoneRequest as StoreRequest;
use App\Models\Filtering\PhoneFilters;
use App\Models\Person;
use App\Models\Phone;

class PhoneController extends ResourceController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $phones = Phone::filterOrFail(new PhoneFilters($request))->jsonPaginate();

        return $this->paginator($phones);
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
        $phone = new Phone($request->resourceAttributes());
        $phone->id = $request->resourceId();
        $phone->saveOrFail();

        $personUuid = $request->resourceRelationships()->oneToOne('person.uuid');
        $phone->person()->attach(Person::encodeUuid($personUuid));

        return $this->created($phone);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Phone  $phone
     * @return \Illuminate\Http\Response
     */
    public function show(Phone $phone)
    {
        return $this->item($phone);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Phone  $phone
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Phone $phone)
    {
        $phone->delete();

        return $this->response->noContent();
    }
}
