<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\AddressRequest as StoreRequest;
use App\Http\Transformers\AddressTransformer;
use App\Models\Address;
use App\Models\Filtering\AddressFilters;
use App\Models\Person;

class AddressController extends ResourceController
{
    /** @var string */
    protected $transformer = AddressTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $addresses = Address::filterOrFail(new AddressFilters($request))->jsonPaginate();

        return $this->paginator($addresses);
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
        $address = new Address($request->resourceAttributes());
        $address->id = $request->resourceId();
        $address->saveOrFail();

        $personUuid = $request->resourceRelationships()->oneToOne('person.uuid');
        $address->person()->attach(Person::encodeUuid($personUuid));

        return $this->created($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        return $this->item($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreRequest  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(StoreRequest $request, Address $address)
    {
        $address->fill($request->resourceAttributes())->saveOrFail();

        $request->resourceRelationships()->oneToMany()->each(function ($data, $name) use ($address) {
            $address->$name()->sync(array_map(function ($relationship) {
                return Address::encodeUuid($relationship['uuid']);
            }, $data));
        });

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return $this->response->noContent();
    }
}
