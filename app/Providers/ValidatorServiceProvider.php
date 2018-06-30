<?php

namespace App\Providers;

use App\Services\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('list', function ($attribute, $value) {
            return is_array($value) || is_string($value);
        });

        Validator::extend('numbers', function ($attribute, $value) {
            return Rule::commaSeparatedNumbers()->passes($attribute, $value);
        });

        Validator::extend('shortdate', function ($attribute, $value) {
            return Validator::make(compact('value'), ['value' => 'date_format:"Y-m-d"'])->passes();
        }, trans('validation.date_format', ['format' => 'YYYY-MM-DD']));

        Validator::extend('uuid', function ($attribute, $value) {
            return Uuid::isValid($value);
        }, trans('validation.uuid'));
    }
}
