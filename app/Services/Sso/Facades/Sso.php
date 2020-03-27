<?php

namespace App\Services\Sso\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Sso\SsoService;

class Sso extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app(SsoService::class);
    }
}
