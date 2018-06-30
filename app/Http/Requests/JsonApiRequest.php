<?php

namespace App\Http\Requests;

use App\Models\Filtering\FilterParameters;
use App\Models\Filtering\Sorting;
use App\Services\Validation\Rule;
use Dingo\Api\Http\FormRequest as BaseRequest;

class JsonApiRequest extends BaseRequest
{
    /** @var array */
    public $relationships = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Return validated filter parameters.
     *
     * @return FilterParameters[]
     */
    public function filters()
    {
        $filters = $this->input('filter', []);

        return array_map(function ($name, $value) {
            return new FilterParameters($name, $value);
        }, array_keys($filters), $filters);
    }

    /**
     * Return validation rules for available relationships.
     *
     * @return array
     */
    public function relationshipRules()
    {
        if (empty($this->relationships)) {
            return [];
        }

        return [
            'data.relationships' => Rule::resourceRelationship($this->relationships),
        ];
    }

    /**
     * Return resource object's attributes.
     *
     * @see http://jsonapi.org/format/#document-resource-object-attributes
     * @return array
     */
    public function resourceAttributes()
    {
        return $this->input('data.attributes', []);
    }

    /**
     * Return resource object's id.
     *
     * @return null|string
     */
    public function resourceId()
    {
        return $this->input('data.id');
    }

    /**
     * Return resource object's relationships.
     *
     * @see http://jsonapi.org/format/#document-resource-object-attributes
     * @return RelationshipsCollection
     */
    public function resourceRelationships()
    {
        return new RelationshipsCollection($this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function rules()
    {
        $class = new \ReflectionClass($this);

        return collect($class->getMethods())
            ->filter(function ($method) {
                return ends_with($method->name, 'Rules');
            })
            ->flatMap(function ($method) {
                return call_user_func([$this, $method->name]);
            })
            ->toArray();
    }

    /**
     * Return sorting parameter.
     *
     * @return Sorting
     */
    public function sorting()
    {
        return new Sorting($this->input('sort', ''));
    }
}
