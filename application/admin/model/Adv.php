<?php
namespace app\admin\model;



class City extends \think\Model{
 
	public function getCityList(){
		$where = '';
		if(input('adv_id')){
			$where = "adv_id=".input('adv_id');
		}
		$city_list = db('city')
					->where($where)
					->paginate(8,false,['query'=>['adv_id'=>input('adv_id')]]);

		return $city_list;
	}

	public function getCityInfo(){
		return db('city')->find(input());
	}
}

?>