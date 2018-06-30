<?php

namespace App\Services\Auditing\Resolvers;

use OwenIt\Auditing\Contracts\UrlResolver;

class TextFormatRequest implements UrlResolver
{
    /**
     * @return string
     */
    public static function resolve(): string
    {
        $request = request();

        return vsprintf("%s %s %s\n%s\n%s", [
            $request->getMethod(),
            $request->getRequestUri(),
            $request->getProtocolVersion(),
            (string) $request->headers,
            $request->getContent(),
        ]);
    }
}
