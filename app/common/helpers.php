<?php

    //获取访问的设备号
    function get_device() {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;

        if($is_pc){
            return  1;
        }

        if($is_mac){
            return  2;
        }

        if($is_iphone){
            return  3;
        }

        if($is_android){
            return  4;
        }

        if($is_ipad){
            return  5;
        }

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

    /**
     * 获取微信操作对象（单例模式）
     * @staticvar array $wechat 静态对象缓存对象
     * @param type $type 接口名称 ( Card|Custom|Device|Extend|Media|Oauth|Pay|Receive|Script|User ) 
     * @return \Wehcat\WechatReceive 返回接口对接
     */
    function & load_wechat($type = '') {
        static $wechat = array();
        $index = md5(strtolower($type));
        if (!isset($wechat[$index])) {
            // 定义微信公众号配置参数（这里是可以从数据库读取的哦）
            $options = array(
                'token'           => '', // 填写你设定的key
                'appid'           => 'wx4e3dd0392b7a1683', // 填写高级调用功能的app id, 请在微信开发模式后台查询
                'appsecret'       => '0d4da93b6faa2d2cd0e8929f70d54f83', // 填写高级调用功能的密钥
                'encodingaeskey'  => '', // 填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
                'mch_id'          => '', // 微信支付，商户ID（可选）
                'partnerkey'      => '', // 微信支付，密钥（可选）
                'ssl_cer'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
                'ssl_key'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
                'cachepath'       => '', // 设置SDK缓存目录（可选，默认位置在Wechat/Cache下，请保证写权限）
            );
            \Wechat\Loader::config($options);
            $wechat[$index] = \Wechat\Loader::get($type);
        }
        return $wechat[$index];
    }
