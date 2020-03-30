<?php

namespace Modules\Sso\Services;

use Illuminate\Support\Facades\Cache;

class SsoService
{

    /**
     * 跳转回原系统
     * @param string $request
     * @param string $user
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect($request, $user)
    {
        $ticket = md5($request->getClientIp() . $user->id . time());
        $source = $request->input('source');
        Cache::put($ticket, $user->id, 120);
        if ($source) {
            $url = $source . '?ticket=' . $ticket;
            return redirect($url);
        }
        return false;
    }

    public function ticketAuth($request)
    {
        $userId = Cache::pull($request->input('ticket'));
        if ($userId) {
            return [
                'user_id' => $userId
            ];
        }
        return false;
    }
}
