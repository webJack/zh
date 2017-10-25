<?php
namespace app\admin\model;

class User extends \think\Model{
 
	public function saveInfo(){
		$name = saveAndgetImgSrc(ROOT_PATH."public/static/index/user/img/",'user_img');
		db('user')->insert([
			'user_nickname'=>input('user_nickname'),
			'user_realname'=>input('user_realname'),
			'city_id'=>input('city_id'),
			'user_phone'=>input('user_phone'),
			'user_pwd'=>md5(input('user_pwd')),
			'user_img'=>'index/user/img/'.$name,
			'user_introduction'=>input('user_introduction'),
			]);
	}

	public function editInfo(){
		if(!empty($_FILES['user_img']['tmp_name'])){
			$name = saveAndgetImgSrc(ROOT_PATH."public/static/index/user/img/",'user_img');
			$user_img = 'index/user/img/'.$name;
		}else{
			$user_img = input('img');
		}
		db('user')->where('user_id='.input('user_id'))->update([
			'user_nickname'=>input('user_nickname'),
			'user_realname'=>input('user_realname'),
			'city_id'=>input('city_id'),
			'user_phone'=>input('user_phone'),
			'user_pwd'=>md5(input('user_pwd')),
			'user_img'=>$user_img,
			'user_introduction'=>input('user_introduction'),
		]);
	}

	/*public function get_user_list()
	{
		return db('user')
		->alias('u')
		->field('u.user_id,u.user_nickname,u.user_realname,u.user_phone,c.city_name,u.status')
		->join('city c','u.city_id=c.city_id','left')
		->paginate(5);
	}*/

	public function getUserList()
	{
		//条件查询
		$searchParam = ['query'=>[]];
		$pageParam = ['query'=>[]];

		$user_nickname = input('user_nickname');
		$user_realname = input('user_realname');
		$city_id = input('city_id');
		$user_phone = input('user_phone');

		//昵称
		if($user_nickname){
			$searchParam['query']['$user_nickname'] = ['like','%'.$user_nickname.'%'];
			$pageParam['query']['$user_nickname'] = $user_nickname;
		}

		//真实姓名 
		if($user_realname){
			$searchParam['query']['user_realname'] = ['like','%'.$user_realname.'%'];
			$pageParam['query']['user_realname'] = $user_realname;
		}

		//城市
		if($city_id){
			$searchParam['query']['u.city_id'] = $city_id;
			$pageParam['query']['u.city_id'] = $city_id;
		}

		//手机
		if($user_phone){
			$searchParam['query']['user_phone'] = ['like',$user_phone.'%'];
			$pageParam['query']['user_phone'] = $user_phone;
		}

		$user_list = db('user')
					->alias('u')
					->join('city c','u.city_id=c.city_id','left')
					->field('u.*,c.city_name')
					->where($searchParam['query'])
					->paginate(8,false,$pageParam);

		return $user_list;
	}

	public function getUserInfo()
	{	
		$this_user = input('user_id');
		return db('user')
			->where("user_id='$this_user'")
			->find();
	}

	public function addTutor(){
		$tutor_info = db('tutor_info')->field('concat(education,experience,project,media_reports,introduction,awards) as tutor_lead,concat(studio,occupation) as tutor_identity,address as location,avatar as tutor_img,user_id,realname as tutor_name,city as city_id')->where("user_id=".input('user_id'))->find();

		//$user_info = db('tutor_info')->field('realname as user_realname,city as city_id')->where("user_id=".input('user_id'))->find();
		db('tutor')->insert($tutor_info);
		$tutor_id = db('tutor')->getLastInsID();
		db('compute_tutor')->insert(['tutor_id'=>$tutor_id]);
		//db('user')->where("user_id=".input('user_id'))->update($user_info);

		return $tutor_id;
	}

	public function addTopic($tutor_id){
		$topic_info = db('tutor_topic')->field('topic as topic_title,duration,cate_id,price,concat(topic_introduction,topic_achievement,summary) as topic_content')->where("user_id=".input('user_id'))->find();
		$topic_info['tutor_id'] = $tutor_id;
		db('topic')->insert($topic_info);
		$topic_id = db('topic')->getLastInsID();
		db('compute_topic')->insert(['topic_id'=>$topic_id,'tutor_id'=>$tutor_id]);
	}
}

?>