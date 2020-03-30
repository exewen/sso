<?php


namespace Modules\Sso\Http\Controllers\master;

use App\Services\Api\ApiResponse;
use Modules\Sso\Services\SsoService;
use Illuminate\Http\Request;

class SsoAuthController
{
    public function ticketAuth(Request $request, SsoService $ssoService)
    {
        $data = $ssoService->ticketAuth($request);
        if ($data !== false) {
            return ApiResponse::success($data);
        }
        return ApiResponse::failure(g_API_ERROR, '操作异常');
    }
}
