<?php

namespace Leite\Log;

use Leite\Log\common\enums\LogDriveEnum;
use Leite\Log\common\enums\LogLevelEnum;
use Leite\Log\drive\DatabaseDrive;
use Leite\Log\drive\FileDrive;
use think\contract\LogHandlerInterface;

/**
 * 日志处理驱动类
 * Create by 谭金辉 2024/11/13 17:26:14
 */
class LogHandler implements LogHandlerInterface
{

    /**
     * TP日志记录回调方法
     * @param array $log
     * @return bool
     * @author 谭金辉 2024/11/15 09:53
     */
    public function save(array $log): bool
    {
        try {
            // 根据配置的日志级别过滤日志
            $log = $this->filterLogByLevel($log);

            // 如果过滤后的日志为空，则不记录日志
            if (empty($log)) {
                return true;
            }

            // 获取日志记录驱动配置
            $logDrive = config('leitelog.driver');

            // 根据日志驱动配置，选择不同的日志记录方式
            switch ($logDrive) {
                case LogDriveEnum::DATABASE:
                    // 数据库记录日志
                    DatabaseDrive::getInstance()->handle($log);
                    break;
                case LogDriveEnum::RABBITMQ:
                    // todo 使用MQ记录日志
                    break;
            }

            // 所有的日志都要记录到文件中
            FileDrive::getInstance()->handle($log);

        } catch (\Throwable $exception) {
            // 为了防止日志记录出现异常影响业务，这里捕获异常，不做处理
            echo $exception->getMessage();
        }

        return true;
    }

    /**
     * 根据配置的日志级别过滤日志
     * @author 谭金辉 2024/11/14 09:29
     */
    public function filterLogByLevel($log)
    {
        // 获取日志记录级别
        $logLevelConfig = config('leitelog.logLevel');

        // 转换日志级别的值
        $logLevel       = LogLevelEnum::LOG_LEVEL_MAP[$logLevelConfig] ?? LogLevelEnum::INFO_LEVEL;

        // 当日志级别小于配置的日志级别时，过滤掉不记录日志
        return collect_extend($log)->filter(function ($item, $level) use ($logLevel) {

            // 获取日志级别对应的数值
            $logLevelNum = LogLevelEnum::LOG_LEVEL_MAP[$level] ?? LogLevelEnum::INFO_LEVEL;

            // 如果日志级别小于配置的日志级别，则过滤掉
            return $logLevelNum >= $logLevel;

        })->toArray();
    }
}
