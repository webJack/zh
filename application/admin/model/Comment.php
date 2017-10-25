<?php
namespace app\admin\model;

class Comment extends \think\Model{

	public function getCommentList()
	{
		//条件查询
		$searchParam = ['query'=>[]];
		$pageParam = ['query'=>[]];

		$user_nickname = input('user_nickname');
		$topic_id = input('topic_id');

		//用户
		if($user_nickname){
			$searchParam['query']['user_nickname'] = ['like','%'.$user_nickname.'%'];
			$pageParam['query']['user_nickname'] = $user_nickname;
		}

		//话题
		if($topic_id){
			$searchParam['query']['topic_id'] = ['like',$topic_id.'%'];
			$pageParam['query']['topic_id'] = $topic_id;
		}

		$comment_list = db('comment')
					->alias('c')
					->join('user u','u.user_id=c.user_id')
					->field('c.*,u.user_nickname')
					->where($searchParam['query'])
					->paginate(8,false,$pageParam);

		return $comment_list;
	}

	
}

?>