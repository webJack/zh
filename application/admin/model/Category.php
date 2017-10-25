<?php
namespace app\admin\model;



class Category extends \think\Model{
 
	public function getParentCate(){
		return db('category')
		->field('cate_id,cate_name,parent_id,level')
		->where('level<3')
		->select();
	}

	public function getThirdCate(){
		return db('category')
		->field('cate_id,cate_name,parent_id,level')
		->where('level=3')
		->select();
	}

	public function getAllCate()
	{
		return db('category')
		->field('cate_id,cate_name,parent_id,level')
		->order('level asc')
		->select();
	}

	public function saveInfo(){
		list($parent_id,$level) = explode("_", input('parent_level'));

		db('category')->insert([
			'cate_name'=>input('cate_name'),
			'parent_id'=>$parent_id,
			'level'=>++$level,
			]);
	}

	public function getCateList(){
		$where = '';
		if(input('level')){
			$where = "level=".input('level');
		}
		$cate_list = db('category')
					->where($where)
					->paginate(8,false,['query'=>['level'=>input('level')]]);

		return $cate_list;
	}
}

?>