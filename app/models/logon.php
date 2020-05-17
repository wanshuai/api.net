<?php

	namespace models;

	class logon extends basis{

        public function get_openid($code){
            if(empty($code)) return false;

            $data = $this->get("logon", [
                    "[>]user" => ["user_id" => "id"]
                ], [
                    "user.openid",
                    "user.ctime"
                ], [
                    "logon.code" => $code,
                    "ORDER"      => ["logon.ctime" => "DESC"]
                ]
            );

            $flag = false;
            if(time() - $data['ctime'] > 5*60) $flag = true;
            if(empty($data['openid'])) return false;
            return [$data['openid'], $flag];
        }

        public function add($user_id) {
            $now = time();
            $code = md5($user_id . USER_LOGIN_SALT . $now);
            $save = [
                "user_id"   => $user_id,
                "code"      => $code,
                "type"      => get_device(),
                "ip4"       => get_ip(),
                "ip6"       => get_ip(),
                "ctime"     => $now,
            ];
            $this->insert("logon", $save);
            
            return $code;
        }        

        public function save($user_id) {
            $now = time();
            $code = md5($user_id . USER_LOGIN_SALT . $now);
            
            $this->update("logon", [
                "code"  => $code
            ], [
                "user_id"   => $user_id,
                "ORDER"     => ["ctime" => "DESC"],
                "Limit"     => 1
            ]);
            
            return $code;
        }

	}