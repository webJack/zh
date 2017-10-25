<?php
namespace app\index\model;
 

class Betutor extends \think\Model {

	function getTutorMaterial(){
		$user_id = Session::get('info.user_id');

		//查询行家列表
    	$tutor_material = db('tutor_info')
    				->where('u.city_id=$user_id')
    				->select();
        return $tutor_list;
	}

    function getTutorTopic(){
        $city_id = input('city_id')!=null?intval(input('city_id')):1;

        //查询行家列表
        $tutor_list = db('tutor')
                    ->alias('t')
                    ->join('user u', 't.user_id=u.user_id')
                    ->join('topic tp', 'tp.tutor_id=t.tutor_id')
                    ->field('t.tutor_id,u.user_name,t.tutor_img,t.tutor_identity,t.tutor_lead,u.city_id,sum(tp.meet_number) as meet_number')
                    ->where('u.city_id=$city_id')
                    ->limit(10)
                    ->select();
        return $tutor_list;
    }
}

?>