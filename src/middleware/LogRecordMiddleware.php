<?php

namespace Leite\Log\middleware;

use app\Request;
use Closure;
use think\facade\Log;

/**
 * 日志记录中间件
 * Create by 谭金辉 2024/11/15 16:54:36
 */
class LogRecordMiddleware
{

    /**
     * 处理请求
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @author 谭金辉 2024/11/15 17:03
     */
    public function handle(Request $request, Closure $next)
    {
        // 请求开始时间
        $startTime = microtime(true);

        // 处理请求
        $response = $next($request);

        // 请求结束时间
        $endTime = microtime(true);

        // 请求数据
        $requestData = $request->param();
        // 响应数据
        $responseData = $response->getData();
        // 请求耗时
        $consumeTime = $endTime - $startTime;

        // 请求日志标签
        $logTag = 'request_log.' . request_pathinfo();

        Log::info([
            'logTag'       => $logTag,
            'request'      => $requestData,
            'response'     => $responseData,
            'costTime'     => $consumeTime,
            'method'       => $request->method(),
            'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1025, 1),
        ]);

        return $response;
    }
}
