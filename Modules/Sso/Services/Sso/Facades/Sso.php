<?php

namespace Modules\Sso\Services\Sso\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Sso\Services\Sso\SsoService;

class Sso extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app(SsoService::class);
    }
}
