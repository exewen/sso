<?php

namespace App\Http\Middleware;

use App\Services\Api\ApiResponse;
use App\Services\Api\ApiVersion;
use Closure;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * 内部 Api 中间件
 * Class ApiAuth
 * @package App\Http\Middleware
 */
class ApiAuth
{
    /**
     * 不需要验证token的
     *
     * @var array
     */
    protected $except = [

    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //解析p参数加到request
        try{
            if(isset($request->p)){
                //decode to array
                $request->request->add(is_array($request->p)?$request->p:json_decode($request->p,true));
            }else{
                throw new RuntimeException();
            }
        } catch (\Exception $e) {
            return ApiResponse::init(g_API_ERROR,'parameter p is required.');
        }

        if (!config('app.debug')) { // 如果不是调试模式，则进行验证   !config('app.debug')
            $requestedApiVersion = ApiVersion::get($request);
            // 检查请求的版本是否存在
            if (!ApiVersion::isValid($requestedApiVersion)) return ApiResponse::init(g_API_VERSIONINVALID,'error protocol version.');

            // 检查校验数据完整性，签名暂时不用验证
            $sign = strtoupper($request->header('sign'));
            $_sign = strtoupper(md5(env('API_SIGNCODE','TEST').$request->input('p')));
            if ($sign != $_sign) return ApiResponse::init(g_API_SIGNERROR,'error sign, invalid request!');

            /****** 根据 protocol 版本号替换命名空间 ******/
            $route = $request->route();
            $actions = $route->getAction();

            //根据请求 header 的 protocol 项，获取对方要访问的协议版本（命名空间）
            $apiNamespace = ApiVersion::getNamespace($requestedApiVersion);
            $actions['uses'] = str_replace(
                'master',
                $apiNamespace,
                $actions['uses']
            );
            $route->setAction($actions);
//            dd($route->getAction());
            /****** 根据 protocol 版本号替换命名空间 ******/
        }

        return $next($request);
    }
}
