<?php

namespace Leite\Log\serviceProvider;

use Leite\Log\middleware\LogRecordMiddleware;
use think\Service;

/**
 * 雷特日志服务提供者
 * Create by 谭金辉 2024/11/14 16:14:33
 */
class LeiteLogServiceProvider extends Service
{
    public function register(): void
    {
        // 注册日志配置文件
        $this->app->config->load(__DIR__ . '/../config/leitelog.php', 'leitelog');

        // 注册请求日志记录中间件
        $this->app->middleware->add(LogRecordMiddleware::class);
    }
}
