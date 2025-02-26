<?php

namespace Leite\Log\drive;


use think\facade\Db;

/**
 * Create by 谭金辉 2024/11/13 17:44:33
 */
class DatabaseDrive extends LogDrive
{

    protected function saveLog($logList)
    {
        // 获取存储的表名
        $tableName = $this->getSaveDbTableName();

        // 获取存储数据
        $data = $this->formatSaveData($logList);

        // 写入数据库
        !empty($data) && Db::name($tableName)->insertAll($data);
    }

    /**
     * 格式化存储数据
     * @param $logList
     * @return array
     * @author 谭金辉 2024/11/14 17:18
     */
    private function formatSaveData($logList)
    {
        // 存储格式化后的数据
        $formatData = [];

        foreach ($logList as $log) {

            // 根据规则过滤日志
            if ($this->filterLog($log)) {
                continue;
            }


            // 日志信息
            $logStr = mb_substr(json_encode($log), 0, 65000);

            $formatData[] = [
                'level'     => $log['level'] ?? '',
                'log_tag'   => $log['logTag'] ?? '',
                'log'       => $logStr,
                'trace_id'  => $log['traceID'] ?? '',
                'timestamp' => $log['timestamp'] ?? null,
                'pid'       => $log['pid'] ?? 0
            ];
        }

        return $formatData;
    }

    /**
     * 获取表名
     * @author 谭金辉 2024/11/14 17:14
     */
    private function getSaveDbTableName()
    {
        // 尝试先从配置文件中获取表名
        $tableName = config('leitelog.tableName');

        // 如果配置文件中没有配置表名，则使用 log 作为表名
        if (empty($tableName)) {
            $tableName = 'log';
        }

        return $tableName;
    }

    /**
     * 过滤日志
     * @author 谭金辉 2024/11/15 09:50
     */
    private function filterLog($log): bool
    {

        // 如果没有日志标签，则过滤日志
        if (empty($log['logTag'])) {
            return true;
        }

        return false;
    }
}
