<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\EmailRequest as StoreRequest;
use App\Http\Transformers\EmailTransformer;
use App\Models\Email;
use App\Models\Filtering\EmailFilters;
use App\Models\Person;

class EmailController extends ResourceController
{
    protected $transformer = EmailTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $emails = Email::filterOrFail(new EmailFilters($request))->jsonPaginate();

        return $this->paginator($emails);
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
        $email = new Email($request->resourceAttributes());
        $email->id = $request->resourceId();
        $email->saveOrFail();

        $personUuid = $request->resourceRelationships()->oneToOne('person.uuid');
        $email->person()->attach(Person::encodeUuid($personUuid));

        return $this->created($email);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        return $this->item($email);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Email $email)
    {
        $email->delete();

        return $this->response->noContent();
    }
}
