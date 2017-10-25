<?php
namespace app\index\model;
use \think\Session; 

class Order extends \think\Model {


	function getOrderList(){
		$user_id = Session::get('info.user_id');
		$order_list = db('order')
					->alias('o')
					->join('tutor t', 't.tutor_id=o.tutor_id')
					->join('topic tp', 'tp.topic_id=o.topic_id')
					->field('o.order_id,o.tutor_id,t.tutor_identity,t.tutor_name,t.tutor_img,o.topic_id,tp.topic_title,tp.price,o.create_time,o.question,o.description,o.status')
					->order('o.order_id desc')
					->where("o.user_id='$user_id'")
					->paginate(2);
					//$s = $order_list->all();

		return $order_list;
	}

	function getBookingList(){
		$tutor_id = Session::get('tutor_info.tutor_id');
		$booking_list = db('order')
					->alias('o')
					->join('tutor t', 't.tutor_id=o.tutor_id')
					->join('topic tp', 'tp.topic_id=o.topic_id')
					->join('user u', 'u.user_id=o.user_id')
					->field('o.order_id,o.tutor_id,u.user_nickname,u.user_img,tp.topic_id,tp.topic_title,tp.price,o.create_time,o.question,o.description,o.status')
					->order('o.order_id desc')
					->where("o.tutor_id='$tutor_id'")
					->paginate(2);
					
		return $booking_list;
	}
}

?>