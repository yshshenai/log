<?php
/**
 * 雷特日志配置
 */

use Leite\Log\common\enums\LogLevelEnum;

return [
    // 项目名称标识
    'appName'     => env('leiteLog.log_app_name', env('app.sys_name', '')),

    // 日志输出目录绝对路径（如果为空则默认为runtime/log目录）
    'logFilePath' => env('leiteLog.log_file_path', ''),

    // 日志驱动
    'driver'      => env('leiteLog.log_channel', ''),

    // 日志记录级别
    'logLevel'    => env('leiteLog.log_level', LogLevelEnum::INFO),

    // DB日志存储的表名
    'tableName'   => env('leiteLog.log_table_name', ''),
];
