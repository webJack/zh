<?php
namespace app\admin\model;

class Wish extends \think\Model{

	public function getWishList()
	{
		//条件查询
		$searchParam = ['query'=>[]];
		$pageParam = ['query'=>[]];

		$user_nickname = input('user_nickname');
		$tutor_name = input('tutor_name');

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

		$wish_list = db('wish')
					->alias('w')
					->join('user u','u.user_id=w.user_id')
					->join('tutor t','t.tutor_id=w.tutor_id')
					->field('w.*,u.user_nickname,t.tutor_name')
					->where($searchParam['query'])
					->paginate(8,false,$pageParam);

		return $wish_list;
	}

	
}

?>