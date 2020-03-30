<?php


namespace Modules\Sso\Http\Controllers\master;

use Illuminate\Http\Request;
class SsoAuthController
{
    public function ticketAuth(Request $request)
    {
        return $request->all();
    }
}
