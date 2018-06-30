<?php

namespace App\Services\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class ResourceRelationship implements Rule
{
    /** @var string Error message translation key */
    const MESSAGE_TRANSLATION = 'validation.relationships.invalid_types';

    /** @var string */
    protected $message;

    /** @var array */
    protected $validTypes;

    /**
     * Create a new rule instance.
     *
     * @param  array|string  $types List of valid resource types
     */
    public function __construct($types)
    {
        $this->validTypes = array_filter(is_array($types) ? $types : explode(',', $types));
    }

    public function passes($attribute, $value)
    {
        if (empty($this->validTypes)) {
            return false;
        }

        $providedTypes = $this->providedTypes($value);
        $invalidTypes = array_diff($providedTypes, $this->validTypes);

        if (empty($invalidTypes)) {
            return true;
        }

        $this->message = $this->prepareMessage($invalidTypes);

        return false;
    }

    public function message()
    {
        return $this->message ?: trans_choice(self::MESSAGE_TRANSLATION, 0);
    }

    /**
     * Get list of provided resource types.
     *
     * @param  string  $value
     * @return array
     */
    protected function providedTypes($value)
    {
        return array_filter(array_dot((array) $value), function ($key) {
            return ends_with($key, 'type');
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Prepare translated message.
     *
     * @param  array  $invalidTypes
     * @return string
     */
    protected function prepareMessage($invalidTypes)
    {
        return trans_choice(self::MESSAGE_TRANSLATION, count($invalidTypes), [
            'types' => implode(', ', array_unique($invalidTypes)),
        ]);
    }
}
