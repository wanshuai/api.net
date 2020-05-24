<?php
	
	namespace models;

	use vendor\MyMedoo;

	class basis extends MyMedoo{

		function __construct(){

			$options = [
				'database_type' => 'mysql', // 你的数据库类型
			    'database_name' => 'magic', // 你的数据库名称
			    'server' => 'localhost',    // 你的数据库地址
			    'username' => 'root',       // 你的数据库用户
			    'password' => 'q1w2e3r4',   // 你的数据库密码
			    'prefix'	=> 'fy_'        // 你的数据库前缀
			];

			parent::__construct($options);

		}
	}