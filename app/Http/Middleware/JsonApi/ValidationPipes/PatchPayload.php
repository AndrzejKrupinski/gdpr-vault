<?php

namespace App\Http\Middleware\JsonApi\ValidationPipes;

use Illuminate\Http\Request;

class PatchPayload extends CommonPayload
{
    public function rules(Request $request)
    {
        return [
            'data.id' => sprintf('required|in:%s', $request->resourceId()),
        ];
    }
}
