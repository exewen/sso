<?php

namespace App\Http\Controllers;

use App\Services\Api\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function auth(Request $request)
    {
        return $request->all();
        dd($request->all());
        $res = \Sso::auth($request);
        if ($res !== false) {
            return ApiResponse::success($res['data']);
        }
        return ApiResponse::failure(g_API_ERROR, '操作异常');
    }

}
