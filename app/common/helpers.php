<?php

    //获取访问的设备号
    function get_device() {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;

        if($is_pc) return 1;
        if($is_mac) return 2;
        if($is_iphone) return 3;
        if($is_android) return  4;
        if($is_ipad) return  5;
        return 6;
    }

    //获取访问者的IP
    function get_ip() {
        $ip = FALSE;

        //客户端IP 或 NONE 
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }

        //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);

            if($ip){ 
                array_unshift($ips, $ip); 
                $ip = FALSE; 
            }

            for($i = 0; $i < count($ips); $i++){
                if(!preg_match("/^(10│172.16│192.168)./", $ips[$i])){
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        //客户端IP 或 (最后一个)代理服务器 IP 
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    //返回当前地址
    function request_uri () {
        return HTTP_TYPE.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    //重定向
    function redirect($url) {
        header("Location: $url");
        exit();
    }
