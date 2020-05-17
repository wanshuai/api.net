<?php

	namespace models;

	class slot extends basis{

        public function get_data($user_id, $order=null){
            if(empty($order)) {
                $order = ["ctime" => "DESC"];
            }else{
                $order = ["id" => explode(",", $order)];
            }

            $data = $this->select("slot", [
                "id(item)",
                "slot(name)",
            ], [
                "AND" => [
                    "user_id"  => $user_id,
                    "del"      => 1
                ],
                "ORDER" => $order,
            ]);
            // echo $this->last();

            if(empty($data)) return false;
            return $data;
        }

        public function add($user_id, $name){
            $rst = $this->insert("slot", [
                "user_id"   => $user_id,
                "slot"      => $name,
                "del"       => 1,
                "ctime"     => time()
            ]);
            if(empty($rst)) return false;
            return $this->id();
        }

        public function change($user_id, $id){
            $data = $this->update("slot", [
                "del"   => 2
            ], [
                "AND" => [
                    "user_id"   => $user_id,
                    "id"        => $id
                ]
            ]);
            if(empty($data)) return false;
            return true;
        }
	}