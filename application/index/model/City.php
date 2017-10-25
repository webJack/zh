<?php
namespace app\index\model;
 

class City extends \think\Model {

	public function getCityList()
	{
		return db('city')
		->field('city_id,city_name')
		->select();		
	}

	public function getCurCity($city_id='')
	{		
		$city_id = input('city_id')==null?1:input('city_id');

		return db('city')
		->field('city_id,city_name')
		->where("city_id=".$city_id)
		->find();
	}	
	
}

?>