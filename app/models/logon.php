<?php

	namespace models;

	class logon extends basis{

        public function get_openid_by_code($code, &$type) {
            if(empty($code)) return false;

            $data = $this->get("logon", [
                    "[>]user" => ["user_id" => "id"]
                ], [
                    "user.openid",
                    "logon.ctime"
                ], [
                    "logon.code" => $code,
                    "ORDER"      => ["logon.ctime" => "DESC"]
                ]
            );

            if(empty($data['openid'])) return false;
            if(time() - $data['ctime'] > 5*60){
                $type = 3;
            }else{
                $type = 2;
            }
            return $data['openid'];
        }

        public function get_openid_by_pwd($account, $pwd, $salt) {
            if(empty($account) || empty($pwd)) return false;

            $salt = substr($salt, 0, 4);
            $data = $this
            ->exec("select openid from ".$this->prefix."user where account='{$account}' AND MD5(concat(pwd, ' {$salt}'))='{$pwd}' order by id desc limit 1;")
            ->fetch();

            if(empty($data)) return false;
            return $data['openid'];
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
                "LIMIT"     => 1
            ]);

            return $code;
        }

	}