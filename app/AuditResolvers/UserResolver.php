<?php

namespace App\AuditResolvers;

class UserResolver implements \OwenIt\Auditing\Contracts\UserResolver
{
    public static function resolve()
    {
        return auth()->user();
    }
}
