<?php

namespace Leite\Log\drive;

/**
 * Create by 谭金辉 2024/11/14 14:54:58
 */
class FileDrive extends LogDrive
{
    // 日志文件路径
    private $logFilePath;

    public function __construct()
    {
        // 初始化日志文件路径
        $this->initLogFilePath();

        // 创建日志文件
        $this->createLogFile();
    }

    protected function saveLog($logList)
    {
        // 格式化日志数据
        $logList = $this->formatSaveData($logList);

        // 写入日志
        $this->write($logList);
    }

    /**
     * 格式化日志
     * @author 谭金辉 2024/11/15 12:03
     */
    protected function formatSaveData($logList)
    {

        // 存储格式化后的数据
        $formatData = [];

        // 循环日志列表，格式化日志数据
        foreach ($logList as $log) {

            $logStr = json_encode($log);

            // 格式化数据
            $message = $logStr;

            // 添加数据
            $formatData[] = $message;
        }

        return $formatData;
    }

    /**
     * 获取日志文件路径
     * @author 谭金辉 2024/11/15 11:28
     */
    protected function initLogFilePath()
    {
        // 获取配置的日志文件路径
        $logFilePath = config('leitelog.logFilePath');

        // 如果日志路径配置为空，则使用runtime/log目录作为日志存储目录
        if (empty($logFilePath)) {
            // 默认日志文件路径
            $logFilePath = runtime_path() . 'log' . DIRECTORY_SEPARATOR;
        }

        // 如果日志路径不是以目录分隔符结尾，则添加目录分隔符
        if (substr($logFilePath, -1) != DIRECTORY_SEPARATOR) {
            $logFilePath .= DIRECTORY_SEPARATOR;
        }

        $this->logFilePath = $logFilePath . date('Y-m-d') . '.log';
    }

    /**
     * 写日志
     * @param array $message
     * @return bool
     * @author 谭金辉 2024/11/15 11:53
     */
    protected function write($message): bool
    {
        $info = [];

        foreach ($message as $type => $msg) {
            $info[$type] = is_array($msg) ? implode(PHP_EOL, $msg) : $msg;
        }

        $message = implode(PHP_EOL, $info) . PHP_EOL;

        // 写入日志到文件中
        return error_log($message, 3, $this->logFilePath);
    }

    /**
     * 创建日志文件
     * @author 谭金辉 2024/11/15 14:08
     */
    protected function createLogFile()
    {
        // 检查日志文件路径是否存在，不存在则创建
        $path = dirname($this->logFilePath);

        // 如果目录不存在则创建
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            exec('chown www:www ' . $path);
        }

    }
}
