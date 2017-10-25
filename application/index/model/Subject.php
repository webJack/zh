<?php
namespace app\index\model;
use \think\Session; 

class Subject extends \think\Model {
	function getSubjectList(){
		return db('adv')
			->alias('a')
			->field('tt.tutor_name,tt.tutor_img,tt.tutor_identity,tp.topic_title,a.adv_id,ctt.total_wish')
			->join('city c',"a.adv_id = c.adv_id")
			->join('category cate',"a.cate_id = cate.cate_id")
			->join('topic tp',"tp.cate_id = cate.cate_id")
			->join('tutor tt',"tt.tutor_id = tp.tutor_id")
			->join('compute_tutor ctt',"ctt.tutor_id = tp.tutor_id")
			->distinct(true)
			// ->where("a.adv_id = a")	
			->select()
		;
	}
}

?>