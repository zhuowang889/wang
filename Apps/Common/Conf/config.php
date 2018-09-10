<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_ACTION_ERROR'     =>  APP_PATH.'Admin/message.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  APP_PATH.'Admin/message.html', // 默认成功跳转对应的模板文件
	'MODULE_ALLOW_LIST'     =>   array('Admin','Api'),
    //数据库配置信息
    /* 'DB_TYPE'                       => 'mysql', // 数据库类型
    'DB_HOST'                       => '39.107.253.110', // 服务器地址
    'DB_NAME'                       => 'jinrong', // 数据库名
    'DB_USER'                       => 'daichao_test', // 用户名
    'DB_PWD'                        => 'jinrong!@#$1234', // 密码
    'DB_PORT'                       =>  3306, // 端口
    'DB_CHARSET'                    =>  'utf8', // 字符集
    'DATA_CACHE_TIME'               =>  600, */
		//=========================
					'DB_TYPE'   => 'mysql', // 数据库类型
					'DB_HOST'   => 'localhost', // 服务器地址// 数据库名
					'DB_USER'   => 'root', // 用户名
					'DB_PWD'    => 'root',//数据库密码
					'DB_NAME'   => 'zhuowang',// 数据库名
					'DB_PREFIX' => 'db_', // 数据库表前缀
					'DB_PORT'   => 3306, // 端口 // 数据库表前缀
					'DB_CHARSET'                    =>  'utf8', // 字符集
		//启用多数据库链接,以防在后期中需要调用其他数据库     暂时没有做确切配置
		//'DB_CONFIG1' => 'mysql://root:1234@localhost:3306/thinkphp',
		'URL_MODEL'          => '2', //URL模式
);