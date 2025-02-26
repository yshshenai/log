# leitelog

## 组件引入

leite/log 是Thinkphp日志组件，组件发布在公司的私服平台，
在项目composer.json配置好私服平台地址后，
执行以下命令，可以将组件引入到项目中

```bash
composer require leite/log
# 优化加载
composer du -o
```

## 变更日志驱动
在 config/log.php 中添加以下配置
```php
'channels'     => [
    .....
    
    // 雷特日志组键
    'leiteLog' => [
        'type'           => \Leite\Log\LogHandler::class,
    ]
]
```

## 日志记录方法

日志记录方法和原生的日志组件一样，需要注意的点是我们尽量按照规范化的方式记录日志

```php
Log::info([
            'logTag' => '日志标记',
            'request' => '请求数据',
            'response' => '响应数据',
        ]);
```

## 日志记录规范
为了规范化日志的格式，我们在记录日志时应该按照以下格式记录

日志内容应包含以下字段 


| 字段       | 是否必填 | 类型  | 字段说明                  |
|----------|------|-----|-----------------------|
| logTag   | 是    | 字符串 | 标记这个日志的类型，可以方便快速定位日志  |
| request  | 否    | 数组  | 用于记录输入的数据（例如： 请求内容等）  |
| response | 否    | 数组  | 用于记录输出的内容和我们希望记录的日志内容 |


    

## 日志组件 .env 配置说明

日志的 env 在 leiteLog 下

| 选项值            | 说明                                          |
|----------------|---------------------------------------------|
| log_app_name   | 记录到日志系统的APP名称, 未设置则默认为应用名称                  |
| log_file_path  | 本地日志文件存储路径， 未设置则默认为应用的 runtime/log 目录下      |
| log_channel    | 日志记录驱动 （database ｜ rabbitmq ） 默认值：database  |
| log_level      | 日志记录最小等级                                    |
| log_table_name | 数据库日志记录的表名， 未设置则默认值未 前缀_log                 |

## 日志输出到数据库说明

为了规范化日志记录， 当日志缺少logTag标记时日志不会记录到数据库中

## php 版本需要大于等于7.3

## 注：如果线上存在其他 php 脚本，请确保执行该脚本的用户和 php-fpm 的用户保持一致，否则可能会造成因为权限不一致而无法写入的错误

## 使用说明

该日志组件可以直接替换Thinkphp的日志组件，记录日志的方法和原生的日志组件一样， 支持无缝衔接

## 附录：

### DB日志表结构

```sql
-- auto-generated definition
create table 表前缀_log
(
    id           bigint auto_increment comment '主键'
        primary key,
    level        varchar(10)  default ''                not null comment '日志级别(trace, debug, info, warn, error)',
    log_tag      varchar(255) default ''                not null comment '日志标签',
    log          text                                   null comment '日志完整信息',
    trace_id     char(32)     default ''                not null comment '全链路TraceId',
    pid          int          default 0                 not null comment '进程id',
    timestamp    datetime                               null comment '日志触发时间',
    created_time datetime     default CURRENT_TIMESTAMP not null comment '创建时间'
)
    comment '日志记录';

create index idx_log_tag
    on oms_log (log_tag);

create index idx_trace_id
    on oms_log (trace_id);

```

## 全局监控的LogTag

| logTag             | 说明          | 内容                                                                       |
|--------------------|-------------|--------------------------------------------------------------------------|
| request_log.请求接口路径 | 记录所有的接口请求日志 | request 请求参数 response为响应内容 costTime 接口处理耗时 method 请求方式 memory_usage 内存消耗 |



