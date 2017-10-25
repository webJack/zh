<?php
namespace app\index\model;
use \think\Session; 

class Wish extends \think\Model {


	function getWishList(){
		$user_id = Session::get('info.user_id');
		$wish_list = db('wish')
					->alias('w')
					->join('tutor t', 't.tutor_id=w.tutor_id')
					->field('w.wish_id,w.tutor_id,t.tutor_identity,t.tutor_name,t.tutor_img')
					->order('w.wish_id desc')
					->where("w.user_id='$user_id'")
					->paginate(2);

		$topic_list = [];
		foreach ($wish_list as $key => $value) {
			$tutor_id = $value['tutor_id'];
			$topic_list[$key] = db('topic')
    				->field('topic_id,topic_title,price')
    				->where("tutor_id='$tutor_id'")
    				->select();
		}

		return ['wish_list' => $wish_list,'topic_list' => $topic_list];
	}
}

?>