<?php

	namespace controllers;

    use models\user;
    use models\logon;

	class base{

		protected $user;

		function __construct() {
			$type = 1; //是否写cookie 1写并且新增 2不写 3写但是只修改不新增
			$openid = false;

			$logon = new logon();
			if(isset($_COOKIE["code"])) {
				$openid = $logon->get_openid_by_code($_COOKIE['code'], $type);
			}
			
			// if($openid === false && isset($_POST["salt"])) {
			if($openid === false) {
				$account = $_POST["account"];
				$pwd = $_POST["pwd"];
				$salt = $_POST["salt"];
				$openid = $logon->get_openid_by_pwd($account, $pwd, $salt);
			}

			if($openid === false) {
				$this->error(100, "请登录");
			}
				// echo $type;
			$user = new user();
			$this->user = $user->get_user($openid, $type);
			if(empty($this->user)) $this->error();
		}

		//返回成功信息
		protected function success($data = [], $extend = []){
			$result = [
				"status" => 200,
				"msg"	 => "",
				"data"	 => $data
			];
			
			if(!empty($extend)){
				unset($extend["status"], $extend['data']);
				$result = array_merge($result, $extend);
			}

			$this->out8exit($result);
		}

		//返回失败信息
		protected function error($code = 404, $msg = "网络故障，请稍后重试！"){
			if($code == 200) $code = 404;

			$result = [
				"status" => $code,
				"msg"	 => $msg,
				"data"	 => []
			];

			$this->out8exit($result);
		}

		private function out8exit($data){
			echo json_encode($data);

			exit();
		}

	}