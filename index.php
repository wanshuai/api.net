<?php

	require('vendor/autoload.php');
	
	// 解决跨域
	header('Access-Control-Allow-Methods:POST');
	header('Access-Control-Allow-Headers: *');
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Max-Age:86400'); // 允许访问的有效期

	use NoahBuscher\Macaw\Macaw;
		
	Macaw::post('/magic', "controllers\index@index");
	Macaw::post('/start', "controllers\index@start");
	Macaw::post('/add', "controllers\index@add");
	Macaw::post('/edit', "controllers\index@edit");
	Macaw::post('/delete', "controllers\index@delete");
	
	Macaw::dispatch();