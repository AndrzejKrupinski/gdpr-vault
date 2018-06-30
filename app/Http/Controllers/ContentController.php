<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonApiRequest;
use App\Http\Requests\ContentRequest as StoreRequest;
use App\Http\Transformers\ContentTransformer;
use App\Models\Content;
use App\Models\Filtering\ContentFilters;

class ContentController extends ResourceController
{
    /** @var string */
    protected $transformer = ContentTransformer::class;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\JsonApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(JsonApiRequest $request)
    {
        $contents = Content::filterOrFail(new ContentFilters($request))->jsonPaginate();

        return $this->paginator($contents);
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
        $content = new Content($request->resourceAttributes());
        $content->id = $request->resourceId();
        $content->saveOrFail();

        return $this->created($content);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        return $this->item($content);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreRequest  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(StoreRequest $request, Content $content)
    {
        $content->fill($request->resourceAttributes())->saveOrFail();

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Content $content)
    {
        $content->delete();

        return $this->response->noContent();
    }
}
