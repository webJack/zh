<?php
namespace app\admin\model;

class Order extends \think\Model{

	public function getOrderList()
	{
		//条件查询
		$searchParam = ['query'=>[]];
		$pageParam = ['query'=>[]];

		$user_nickname = input('user_nickname');
		$tutor_name = input('tutor_name');
		$topic_id = input('topic_id');

		//用户
		if($user_nickname){
			$searchParam['query']['user_nickname'] = ['like','%'.$user_nickname.'%'];
			$pageParam['query']['user_nickname'] = $user_nickname;
		}

		//行家 
		if($tutor_name){
			$searchParam['query']['tutor_name'] = ['like','%'.$tutor_name.'%'];
			$pageParam['query']['tutor_name'] = $tutor_name;
		}

		//话题
		if($topic_id){
			$searchParam['query']['topic_id'] = ['like',$topic_id.'%'];
			$pageParam['query']['topic_id'] = $topic_id;
		}

		$order_list = db('order')
					->alias('o')
					->join('user u','u.user_id=o.user_id')
					->join('tutor t','t.tutor_id=o.tutor_id')
					->field('o.*,u.user_nickname,t.tutor_name')
					->where($searchParam['query'])
					->paginate(8,false,$pageParam);

		return $order_list;
	}

	
}

?>