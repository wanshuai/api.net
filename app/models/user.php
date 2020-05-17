<?php

	namespace models;

	class user extends basis{

        /*
        * 获取用户信息
        * @param $openid openid
        * @type 是否写入cookie 1写 2不写 3写但是只修改不新增
        */
        public function get_user($openid, $type){
            if(empty($openid)) return false;

            $data = $this->get("user", "*", ["openid" => $openid]);

            if(empty($data)) {
                $data = ["openid"=>$openid,"ctime"=>time()];
                $rst = $this->insert("user", $data);
                if(empty($rst)) return false;

                $data["id"] = $this->id();
                $type = 2;
            }

            $logon = new logon();
            if($type == 2){
                $code = $logon->add($data["id"]);
                setcookie("code", $code, time()+(7*24*60*60), '/magic', 'magic.gfuzy.com');
            }else if($type == 3){
                $code = $logon->save($data["id"]);
                setcookie("code", $code, time()+(7*24*60*60), '/magic', 'magic.gfuzy.com');
            }
            
            return $data;
        }

        /**
         * 获取首页的3个参数
         * 如果加了最后一个参数则是获取时间修改后的变化值
         */
        public function get_data($user_id, $order=null, $slot_id=0) {
            $magic = new magic();
            list($data, $before) = $magic->get_data($user_id);
            if(empty($data)) return [];

            $slot = new slot();
            $slot = $slot->get_data($user_id, $order);
            if(empty($slot)) $slot = [];

            $new = 0; //添加或修改时间后法术位已使用的时间
            $minutes = new minutes();
            foreach($slot as $key => $val) {
                $time = $minutes->get_data($val['item'], $before);
                $slot[$key]["minutes"] = $time ?: 0;
                $data["minutes"] -= $time;
                if($val['item'] == $slot_id) $new = $time;
            }
            
            if($data["minutes"] <= 0) $data["minutes"] = 0;
            $data["slot"] = $slot;

            //分别对应index和edit里的方法
            if($slot_id == 0){
                return $data;
            }else{
                $data = ["total" => $data["minutes"], "new" => $new];
                return $data;
            }
        }

	}