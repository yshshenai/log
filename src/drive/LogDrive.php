<?php

namespace Leite\Log\drive;

/**
 * 日志记录驱动接口
 * Create by 谭金辉 2024/11/13 17:46:06
 */

use Leite\Log\LogTemplate;

abstract class LogDrive
{
    protected static $insTance;

    /**
     * 单例模式
     * @return static
     * @author 谭金辉 2024/11/13 18:06
     */
    public static function getInstance()
    {
        if (!isset(static::$insTance) || empty(static::$insTance) || !(static::$insTance instanceof static)) {
            static::$insTance = new static();
        }
        return static::$insTance;
    }

    /**
     * 处理日志
     * @param array $log
     * @author 谭金辉 2024/11/13 17:46
     */
    public function handle(array $logList): bool
    {
        $logFormatList = [];

        // 目前TP的传输的日志格式是['info' => [日志1, 日志2, 日志3], 'error' => [日志1, 日志2, 日志3]] 因此需要使用两层循环进行遍历
        foreach ($logList as $level => $levelLogList) {

            // 二次循环
            foreach ($levelLogList as $log) {

                // 格式化日志
                $logFormatList[] = LogTemplate::getInstance()->formatLog($level, $log);
            }

        }

        // 触发对应驱动的日志存储方法
        $this->saveLog($logFormatList);

        return true;
    }

    /**
     * 存储日志方法
     * @author 谭金辉 2024/11/14 09:48
     */
    protected abstract function saveLog($logList);

}
