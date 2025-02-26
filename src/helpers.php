<?php
/**
 * Create by 谭金辉 2024/11/15 18:23:01
 */

use think\facade\Log;

if (!function_exists('debug')) {

    /*
     * debug 日志格式化的日志信息
     */
    function debug(string $logTag, $request = [], $response = [], $option = [])
    {
        baseLog('debug', $logTag, $request, $response, $option);
    }
}

if (!function_exists('info')) {

    /*
     * info 日志格式化的日志信息
     */
    function info(string $logTag, $request = [], $response = [], $option = [])
    {
        baseLog('info', $logTag, $request, $response, $option);
    }
}

if (!function_exists('warning')) {

    /*
     * warning 日志格式化的日志信息
     */
    function warning(string $logTag, $request = [], $response = [], $option = [])
    {
        baseLog('warning', $logTag, $request, $response, $option);
    }
}


if (!function_exists('error')) {
    /*
     * error 日志格式化的日志信息
     */
    function error(string $logTag, $request, array $response = [], array $option = [],  Throwable $e = null)
    {
        // 如果未传入异常类则直接记录日志
        if (empty($e)) {
            baseLog('error', $logTag, $request, $response, $option);
            return;
        }

        // 如果传入异常类则记录异常信息
        Log::error([
            'logTag'    => $logTag,
            'path'      => [
                'msg'   => $e->getMessage(),
                'class' => get_class($e),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ],
            'request'   => $request,
            'response'  => $response,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}

if (!function_exists('baseLog')) {

    /*
     * 基础日志格式化的日志信息方法
     */
    function baseLog($logType, string $logTag, $request, array $response = [], array $option = [])
    {
        $logInfo = [
            'logTag'    => $logTag,
            'request'   => $request,
            'response'  => $response,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // 拼接扩展信息
        $logInfo = array_merge($logInfo, $option);

        Log::$logType($logInfo);
    }
}
