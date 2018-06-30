<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;

class RelationshipsCollection
{
    /** @var array List of contactable resources */
    protected const CONTACTABLE = ['addresses', 'emails', 'phones'];

    /** @var Collection */
    protected $items;

    /**
     * Return an array containing column name and identifier.
     *
     * @param  array  $relationship
     * @return array
     */
    protected static function normalizeRelationship($relationship)
    {
        return [
            'key' => str_singular($relationship['type']) . '_id',
            'type' => $relationship['type'],
            'uuid' => $relationship['id'],
        ];
    }

    public function __construct(JsonApiRequest $request)
    {
        $this->items = Collection::make($request->input('data.relationships'))
            ->partition(function ($relationships) {
                return array_has($relationships, 'data.type');
            })
            ->transform(function (Collection $partition) {
                return $partition->mapWithKeys(function ($relationship, $relationName) {
                    $data = $relationship['data'];
                    $isOneToOne = isset($data['type']);

                    $normalizedData = array_map(
                        'static::normalizeRelationship',
                        $isOneToOne ? [$data] : $data
                    );

                    return [$relationName => $normalizedData];
                });
            });
    }

    /**
     * Get the collection of contactable relationships.
     *
     * @return Collection
     */
    public function contactable()
    {
        return $this->oneToMany()->filter(function ($relationships) {
            foreach ($relationships as $relationship) {
                if (in_array($relationship['type'], self::CONTACTABLE)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Get the collection of one-to-one relationships.
     *
     * @param  string|null  $name
     * @return mixed
     */
    public function oneToOne(string $name = null)
    {
        /** @var Collection $relationships */
        $relationships = $this->items->first();

        if ($name === null) {
            return $relationships->flatten(1);
        }

        if (! str_contains($name, '.')) {
            return array_first($relationships->get($name));
        }

        list($key, $path) = explode('.', $name);

        return array_get(array_first($relationships->get($key)), $path);
    }

    /**
     * Get the collection of one-to-many relationships.
     *
     * @return Collection
     */
    public function oneToMany()
    {
        return $this->items->last();
    }
}
