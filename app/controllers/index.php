<?php

    namespace controllers;

    use models\user;
    use models\magic;
    use models\slot;
    use models\minutes;
    
    class index extends base {

        public function index() {
            $user_id = $this->user["id"];
            $order = $_COOKIE["order"];

            $user = new user();
            $data = $user->get_data($user_id, $order);
            if($data === false) $this->error();

            $this->success($data);
        }

        public function start() {
            $user_id = $this->user["id"];
            $start = $_POST['start'];
            $loop = $_POST['loop'];
            $used = $_POST['used'];

            $magic = new magic();
            $rst = $magic->check($user_id, [
                "next"  => $start,
                "loop"  => $loop,
                "used"  => $used
            ]);
            if(empty($rst)) $this->error();
            $this->index();
        }

        public function add() {
            $user_id = $this->user["id"];
            $name = $_POST["name"];

            $slot = new slot();
            $rst = $slot->add($user_id, $name);
            if(empty($rst)) $this->error();
            
            $data = [
                "item"      => $rst,
                "name"      => $name,
                "minutes"   => 0,
            ];
            $this->success($data);
        }

        public function edit() {
            $user_id = $this->user["id"];
            $id = $_POST['item'];
            $time = $_POST["minutes"];
            $type = intval($_POST['type']) + 1;

            $minutes = new minutes();
            $rst = $minutes->add($user_id, $id, $time, $type);
            if(empty($rst)) $this->error();
            
            $user = new user();
            $data = $user->get_data($user_id, null, $id);
            $this->success($data);
        }

        public function delete() {
            $user_id = $this->user["id"];
            $id = $_POST["item"];

            $slot = new slot();
            $rst = $slot->change($user_id, $id);
            if(empty($rst)) $this->error();
            
            $user = new user();
            $data = $user->get_data($user_id);
            $this->success(["total" => $data['minutes']]);
        }

    }