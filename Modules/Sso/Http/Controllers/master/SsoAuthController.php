<?php


namespace Modules\Sso\Http\Controllers\master;

use Illuminate\Http\Request;
class SsoAuthController
{
    public function index(Request $request)
    {
        dd($request->all());
    }
}
