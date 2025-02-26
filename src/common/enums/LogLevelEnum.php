<?php

namespace Leite\Log\common\enums;

/**
 * 日志级别
 * Create by 谭金辉 2024/11/13 17:50:40
 */
class LogLevelEnum
{
    const TRACE = 'trace';

    const SQL = 'sql';

    // 调试
    const DEBUG = 'debug';

    // 信息
    const INFO = 'info';

    // 警告
    const WARN = 'warn';

    // 错误
    const ERROR = 'error';

    const TRACE_LEVEL = 100;

    const SQL_LEVEL = 200;

    const DEBUG_LEVEL = 300;

    const INFO_LEVEL = 400;

    const WARN_LEVEL = 500;

    const ERROR_LEVEL = 600;

    // 日志等级级别映射
    const LOG_LEVEL_MAP = [
        self::TRACE => self::TRACE_LEVEL,
        self::DEBUG => self::DEBUG_LEVEL,
        self::INFO  => self::INFO_LEVEL,
        self::WARN  => self::WARN_LEVEL,
        self::ERROR => self::ERROR_LEVEL,
        self::SQL   => self::SQL_LEVEL,
    ];
}
