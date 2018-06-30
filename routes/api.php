<?php

/** @var \Dingo\Api\Routing\Router $api Declared in the RouteServiceProvider::map() */

$api->resources([
    'addresses' => 'AddressController',
    'consents' => 'ConsentController',
    'contents' => 'ContentController',
    'emails' => ['EmailController', ['except' => 'update']],
    'people' => 'PersonController',
    'personaldetails' => ['PersonalDetailController', ['except' => 'update']],
    'phones' => ['PhoneController', ['except' => 'update']],
    'purposes' => 'PurposeController',
    'queries' => ['QueryController', ['only' => ['show', 'store']]],
]);
