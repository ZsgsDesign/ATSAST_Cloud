# ATSAST_Cloud
Netdisk for ATSAST

## NOTICE

For better reference, all materials below would be written in Chinese.

# ATSAST网盘系统

这是ATSAST网盘系统的独立源代码，为ATSAST源代码的一部分，本系统代码将会被整合于ATSAST，但将作为单独部分开源并可独立使用。

## 安装

新建数据库，并导入项目根目录下的sastdisk.sql

新建/protected/model/CONFIG.php，插入内容如下

```php
<?php

class CONFIG {

	public static function GET($KEY)
	{
		$config=array(
			"ATSAST_MYSQL_HOST"=>"localhost",
			"ATSAST_MYSQL_PORT"=>"3306",
			"ATSAST_MYSQL_USER"=>"root",
			"ATSAST_MYSQL_DATABASE"=>"sastdisk",
			"ATSAST_MYSQL_PASSWORD"=>"root",

			"ATSAST_CDN"=>"https://static.1cf.co",
			"ATSAST_SALT"=>"@SAST+1s",
			"ATSAST_DOMAIN"=>"",

			"CLOUD_FILE_DIRECTORY"=>"/home/cloud_objs"
		);
		return $config[$KEY];
	}
	

}
```

## 部署地址
https://disk.winter.mundb.xyz

## 基本功能

预期实现的基本功能包括但不限于：

1. 注册登录；
1. 文件上传下载；
1. 上传进度显示；
1. 文件以及文件夹的新建删除；
1. 文件回收站的清空和恢复；
1. 文件分享；
1. 加密/时限分享；
1. 当前文件夹搜索和当前网盘全局搜索；
1. 急速快传；
1. 同名处理等；
1. 一般网盘的其他基础功能。

## 额外要求

+ 开发过程中，开发团队应该完成开发日志；
+ 编写项目文档（介绍各个功能）与接口文档（如果有）。

## 推荐

后端框架：`FlashPHP`

前端库：`Bootstrap Material Design`

数据库：`MySQL`

基本语言：`PHP`、`JS`、`CSS`

## 其他注意事项

因项目为ATSAST源码一部分，项目协议转为AGPL。
