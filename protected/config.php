<?php

date_default_timezone_set('PRC');

$config = array(
    'rewrite' => array(
        'account/register'                                   => 'account/index',
        'account/login'                                      => 'account/index',
        'account/<a>'                                        => 'account/<a>',
        'account/'                                           => 'account/index',
        'cloud/'                                             => 'cloud/index',
        '<a>'                                                => 'main/<a>',
        '/'                                                  => 'main/index',
    ),
);

$domain = array(
    "disk.winter.mundb.xyz" => array( // 生产配置
        'debug' => 0,
        'maintain' => 0,
        'mysql' => array(
            'MYSQL_HOST' => "localhost",
            'MYSQL_PORT' => "3306",
            'MYSQL_USER' => "root",
            'MYSQL_DB'   => "sastdisk",
            'MYSQL_PASS' => "root",
            'MYSQL_CHARSET' => 'utf8',
        ),
    ),
    "dbg.disk.winter.mundb.xyz" => array( // 调试配置
        'debug' => 1,
        'maintain' => 0,
        'mysql' => array(
            'MYSQL_HOST' => "localhost",
            'MYSQL_PORT' => "3306",
            'MYSQL_USER' => "root",
            'MYSQL_DB'   => "sastdisk",
            'MYSQL_PASS' => "root",
            'MYSQL_CHARSET' => 'utf8',
        ),
    ),
); // 反正到时候要合并到ATSAST，这边的配置就随便写写凑活了（什么？你的数据库不是rootroot？我不听我不听）

// 为了避免开始使用时会不正确配置域名导致程序错误，加入判断
if(empty($domain[$_SERVER["HTTP_HOST"]])) die("配置域名不正确，请确认".$_SERVER["HTTP_HOST"]."的配置是否存在！");

return $domain[$_SERVER["HTTP_HOST"]] + $config;