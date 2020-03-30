<?php

namespace App\Http\Middleware;

use Closure;

class CasAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 未登录进行
        if (true) {
            $ticket = $request->input('ticket');
            if ($ticket) {
                $result = \Authority::getLoginByTicket($ticket);
                if ($result['status'] === 200) {
                    // 登录
                    dd(22);
                }
            }
            $ssoUrl = env('SSO_API_BASE_URL') . '/sso?source=' . urlencode(env('APP_URL')) . '&appid=1&appsecret=2';
            return redirect($ssoUrl);
        }
        return $next($request);
    }
}
