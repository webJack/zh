<?php
namespace app\index\model;
 

class Tutor extends \think\Model {

	function getTutorList(){
		$city_id = input('city_id')!=null?input('city_id'):'1';

        //查询行家
        $tutor = db('tutor')
                    ->field('tutor_id')
                    ->where("city_id='$city_id'")
                    ->order('tutor_id')
                    ->limit(10)
                    ->select();

        //查询约见人数最多的话题
        $topic_list = [];
        foreach ($tutor as $key => $value) {
            $topic_list[$key] = db('compute_topic')
                    ->field('topic_id')
                    ->where("tutor_id=".$value['tutor_id']." and meet_number=(select max(meet_number))")
                    ->find();
        }

        //查询行家列表
        $tutor_list = [];
        foreach ($topic_list as $key => $value) {
            $tutor_list[$key] = db('tutor')
                    ->alias('t')
                    ->join('topic tp','tp.tutor_id=t.tutor_id')
                    ->join('compute_tutor ct', 'ct.tutor_id=t.tutor_id')
                    ->field('t.tutor_id,t.tutor_name,t.tutor_img,t.tutor_identity,LEFT(t.tutor_lead,56) as tutor_lead,t.city_id,ct.total_meet,tp.topic_id,tp.topic_title')
                    ->where('tp.topic_id='.$value['topic_id'])
                    ->find();
        }

        //print_r($tutor_list);exit();

        return $tutor_list;
	}

	function getTutor(){

        $tutor_id = input('tutor_id');

		//查询行家信息
    	$tutor_info = db('tutor')
    				->alias('t')
                    ->join('city c', 'c.city_id=t.city_id')
                    ->join('compute_tutor ct', 'ct.tutor_id=t.tutor_id')
    				->field('t.tutor_id,t.tutor_identity,t.location,c.city_id,c.city_name,t.tutor_name,t.tutor_img,t.tutor_lead,ct.total_meet,ct.total_wish')
    				->where("t.tutor_id='$tutor_id'") 
    				->find();

        return $tutor_info;
	}
}

?>