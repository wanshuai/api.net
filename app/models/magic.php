<?php

	namespace models;

	class magic extends basis{

        public function get_data($user_id){
            $info = $this->get("magic", [
                "next",
                "loop",
                "used(minutes)",
            ],[
                "AND"   => [
                    "user_id"   => $user_id,
                    "del"       => 1,
                ]
            ],[
                "ORDER" => ["ctime" => "DESC"]
            ]);
            if(empty($info)) return [[], 0];

            list($before, $next) = $this->getTime($info);
            $data = [
                "minutes"   => $info["minutes"] ?: 0,
                "next"      => date("m月d日", $next)
            ];

            return [$data, $before];
        }

        public function check($user_id, $data){
            if(empty($data)) return false;
            $oldData = $this->get("magic", '*', ["user_id"=>$user_id]);
            $newDate = [
                "user_id"   => $user_id,
                "next"      => $data['next'],
                "loop"      => $data['loop'],
                "used"      => $data['used'],
                "del"       => 1,
                "ctime"     => time()
            ];

            $rst = false;
            if(empty($oldData)){
                $rst = $this->insert("magic", $newDate);
            }else{
                $rst = $this->actionWithParams(function($obj, $data){
                    $rst = $obj->update("magic", ["del"=>2], ["user_id"=>$data['user_id']]);
                    if(empty($rst)) return false;
                    $rst = $this->insert("magic", $data);
                    if(empty($rst)) return false;
                    return true;
                }, $newDate);
            }
            if(empty($rst)) return false;
            return true;
        }

        //获取这个loop和下个loop之间的时间
        private function getTime($data){
            //根据下一个loop的时间计算上一个loop的时间
            function getBefore($next, $year, $flag){
                $month = intval(date("m", $next)) - $flag ? 2 : 1;
                if($month <= 0){
                    $year -= 1;
                    $month += 12;
                }
                return strtotime( $year."-".$month."-".getDay8Month($month, date("d", $next)) );
            }
            //检查day是否合法
            function getDay8Month($month, $day, $next=false){
                $days = [31,0,31,30,31,30,31,31,30,31,30,31];
                if($month < 3 && $next){
                    $year = intval(date("Y")) + 1;
                }else{
                    $year = date("Y");
                }
                $days[1] = date("L", $year) ? 29 : 28;

                if($day == 0) return $days[$month-1];
                if($day > $days[$month-1]) return $days[$month-1];
                return $day;
            }
            $before = 0;
            $next = 0;
            $now = strtotime("today");
            $time = $now - $data["next"];
            if($time < 0) {
                $next = $data['next'];
            }else{
                if(in_array($data['loop'], [1,2,3])){
                    $mod = $time % ($data['loop']*7*24*60);
                    $next = $time + ($data['loop']*7*24*60 - $mod);
                    $before = $data["next"] - $data['loop']*7*24*60;
                }else{
                    $year = intval(date("Y", $now));
                    $month = 0;
                    $flag = false;
                    if($data['loop'] == 4){
                        if(date("d", $now) > date("d", $data['next'])){
                            $month = (intval(date("m", $now)) + 1);
                            if($month > 12){
                                $month = 1;
                                $year += 1;
                            }
                        }else{
                            $month = date("m", $now);
                        }
                    }else{
                        $flag = true;
                        if((date("m", $now) + date("m", $data['next']))%2){
                            $month = (intval(date("m", $now)) + 1);
                        }else{
                            if(date("d", $now) > date("d", $data['next'])){
                                $month = (intval(date("m", $now)) + 2);
                            }else{
                                $month = date("m", $now);
                            }
                        }
                        if($month > 12){
                            $year += 1;
                            $month -= 12;
                        }
                    }
                    $next = strtotime( $year."-".date("m", $now)."-".getDay8Month($month, date("d", $data['next']), $flag) );
                    $before = getBefore($next, $year, $flag);
                } 
            }
            return [$before, $next];
        }

	}