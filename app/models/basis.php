<?php
	
	namespace models;

	use vendor\MyMedoo;

	class basis extends MyMedoo{

		function __construct(){

			$options = [
				'database_type' => 'mysql',
			    'database_name' => 'magic',
			    'server' => 'localhost',
			    'username' => 'root',
			    'password' => 'q1w2e3r4',
			    'prefix'	=> 'fy_'
			];

			parent::__construct($options);

		}
	}