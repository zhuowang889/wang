<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_ACTION_ERROR'     =>  APP_PATH.'Admin/message.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  APP_PATH.'Admin/message.html', // 默认成功跳转对应的模板文件
	'MODULE_ALLOW_LIST'     =>   array('Admin','Api','Sso'),
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
					'DB_NAME'   => 'sso',// 数据库名
					'DB_PREFIX' => 'db_', // 数据库表前缀
					'DB_PORT'   => 3306, // 端口 // 数据库表前缀
					'DB_CHARSET'                    =>  'utf8', // 字符集
		//启用多数据库链接,以防在后期中需要调用其他数据库     暂时没有做确切配置
		//'DB_CONFIG1' => 'mysql://root:1234@localhost:3306/thinkphp',
		'URL_MODEL'          => '2', //URL模式
		
		'DATA_CACHE_PREFIX' => '',//缓存前缀
		'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
		'REDIS_RW_SEPARATE' => false, //Redis读写分离 true 开启
		'REDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
		'REDIS_PORT'=>'6379',//端口号
		'REDIS_TIMEOUT'=>'300',//超时时间
		'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
		'REDIS_AUTH'=>'',//AUTH认证密码
		'DATA_CACHE_TIME'=> 0,      // 数据缓存有效期 0表示永久缓存
		/*跨域http_origins*/
        'HTTP_ORIGINS' => ['http://10.8.102.37:8009','http://10.8.66.111:8009','http://127.0.0.1:8080','http://127.0.0.1:8009'],
);