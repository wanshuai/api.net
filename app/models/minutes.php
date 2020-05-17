<?php

	namespace models;

	class minutes extends basis{

        public function get_data($slot_id, $before){
            $data = $this->select("minutes", [
                "type",
                "time"
            ],[
                "AND" => [
                    "slot_id"   => $slot_id,
                    "ctime[>=]" => $before
                ],
                "ORDER" => ["ctime" => "ASC"]
            ]);

            $minutes = 0;
            if(empty($data)) return $minutes;

            foreach($data as $val){
                if($val["type"] == 2){
                    $minutes = $val["time"];
                }else{
                    $minutes += $val["time"];
                }
            }
            return $minutes;
        }

        public function add($user_id, $id, $time, $type){
            $rst = $this->insert("minutes", [
                "user_id"   => $user_id,
                "slot_id"   => $id,
                "type"      => $type,
                "time"      => $time,
                "ctime"     => time()
            ]);

            if(empty($rst)) return false;
            return true;
        }

	}