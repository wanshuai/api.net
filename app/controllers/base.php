<?php

	namespace controllers;

    use models\user;
    use models\logon;

	class base{

		protected $user;

		function __construct() {
			$type = 1; //是否写cookie 1写并且新增 2不写 3写但是只修改不新增
			$openid = "";
			$code = $_COOKIE["code"] ?: $_GET["code"];
			if(empty($code)) {
				$this->goto_wechat();
			}else{
				if($_GET['code']) {
					//判断
					$state = $_GET["state"];
					if($state !== WECHAT_STATE) $this->error();
					//获取openid
					$oauth = & load_wechat("oauth");
					$json = $oauth->getOauthAccessToken();
					if($json === false) $this->error();

					$data = json_decode($json, true);
					$openid = $data['openid'];
				}
				if($_COOKIE['code']) {
					$logon = new logon();
					$data = $logon->get_openid($_COOKIE['code']);
					$flag = false;
					if($data === false){
						$this->goto_wechat();
					}else{
						list($openid, $flag) = $data;
					}
					$type = 2 + $flag ? 1 : 0;
				}
			}
				
			$user = new user();
			$this->user = $user->get_user($openid, $type);
			if(empty($this->user)) $this->error();
		}

		private function goto_wechat(){
			$oauth = & load_wechat("oauth");
			redirect($oauth->getOauthRedirect(request_uri(), WECHAT_STATE));
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