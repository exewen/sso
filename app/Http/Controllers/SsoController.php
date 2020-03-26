<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SsoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        dd(\Auth::user());
        return view('home');
    }

    private function getTicketUrl($source)
    {
        $ticket = md5(time()+key);
        Cache::put($ticket, $user, 120);
        $url = $source . '?ticket=' . $ticket;
        return $url;
    }
}
