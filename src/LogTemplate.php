<?php
/**
 * Create by 谭金辉 2024/11/14 19:04:29
 */

namespace Leite\Log;

class LogTemplate
{
    protected static $instance;

    // 日志格式模版
    protected $logTemplate;

    /**
     * 单例模式
     * @return static
     * @author 谭金辉 2024/11/13 18:06
     */
    public static function getInstance()
    {
        if (!isset(static::$instance) || empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct()
    {
        $this->formatLogTemplate();
    }


    /**
     * 格式化日志
     * @param $level
     * @param $logData
     * @return mixed
     * @author 谭金辉 2024/11/14 09:55
     */
    public function formatLog($level, $logData)
    {
        // 日志信息
        $messageData = [];

        // 将日志数据格式化为数组
        if (is_scalar($logData)) {
            // 如果日志类型是基础数据类型，则将他放置到response中
            $messageData['response'] = $logData;
        } elseif (is_object($logData)) {
            // 如果日志类型是对象，则将对象转换为数组
            $messageData = $logData->toArray();
        } elseif (is_array($logData)) {
            // 如果日志类型是数组，则直接使用
            $messageData = $logData;
        }

        // 填充日志类型
        $messageData['level'] = $level;
        // 填充日志标签
        $messageData['logTag'] = str_replace('/', '.', $messageData['logTag'] ?? '');

        return array_merge($this->logTemplate, $messageData);
    }

    /**
     * 格式化日志模版
     * @author 谭金辉 2024/11/13 18:01
     */
    protected function formatLogTemplate()
    {

        $appName = config('leitelog.appName', '');

        $this->logTemplate = [
            'level'     => '',// 日志等级
            'logTag'    => '',// 日志标签 1
            'appName'   => $appName,// 系统日志名
            'host'      => $_SERVER["SERVER_NAME"] ?? '', // 主机名
            'ip'        => $_SERVER['SERVER_ADDR'] ?? '', // 主机IP
            'pid'       => getmypid(),//进程id
            'url'       => $_SERVER['REQUEST_URI'] ?? '', //页面/接口URI
            'traceID'   => $this->makeTraceId(),// 进程唯一标识
            'timestamp' => date("Y-m-d H:i:s", time()),
            'clientIP'  => $_SERVER['REMOTE_ADDR'] ?? '', //调用者IP
        ];
    }

    /**
     * 生成traceID, 32位的唯一字符串
     * @return string
     */
    protected function makeTraceId()
    {
        return substr(bin2hex(random_bytes(16)), 0, 32);
    }

}
