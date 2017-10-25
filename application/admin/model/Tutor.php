<?php
namespace app\admin\model;

class Tutor extends \think\Model{

	public function editInfo(){
		if(!empty($_FILES['tutor_img']['tmp_name'])){
			$name = saveAndgetImgSrc(ROOT_PATH."public/static/index/tutor/img/",'tutor_img');
			$tutor_img = 'index/tutor/img/'.$name;
		}else{
			$tutor_img = input('img');
		}
		db('tutor')->where('tutor_id='.input('tutor_id'))->update([
			'tutor_name'=>input('tutor_name'),
			'tutor_identity'=>input('tutor_identity'),
			'city_id'=>input('city_id'),
			'location'=>input('location'),
			'tutor_img'=>$tutor_img,
			'tutor_lead'=>input('tutor_lead'),
		]);
	}

	public function getTutorList()
	{
		//条件查询
		$searchParam = ['query'=>[]];
		$pageParam = ['query'=>[]];

		$tutor_name = input('tutor_name');
		$city_id = input('city_id');

		//姓名
		if($tutor_name){
			$searchParam['query']['$tutor_name'] = ['like','%'.$tutor_name.'%'];
			$pageParam['query']['$tutor_name'] = $tutor_name;
		}

		//城市
		if($city_id){
			$searchParam['query']['t.city_id'] = $city_id;
			$pageParam['query']['t.city_id'] = $city_id;
		}

		$tutor_list = db('tutor')
					->alias('t')
					->join('city c','t.city_id=c.city_id','left')
					->field('t.*,c.city_name')
					->where($searchParam['query'])
					->paginate(8,false,$pageParam);

		return $tutor_list;
	}

	public function getTutorInfo()
	{	
		$tutor_id = input('tutor_id');
		return db('tutor')
			->where("tutor_id='$tutor_id'")
			->find();
	}

}

?>