<?php
namespace app\admin\model;

class Topic extends \think\Model{
 	
 	public function saveInfo(){
		db('topic')->insert([
			'topic_title'=>input('cmd_name'),
			'cate_id'=>input('cmd_cate'),
			'topic_content'=>input('cmd_content')
			]);
	}

	public function getCateList()
	{				
		$cate_id = input('cate_id');
		$where = '';
		if($cate_id){
			$cateItem = db('category')->field('cate_name,cate_id,level')->where("cate_id=$cate_id")->find();
			if($cateItem['level'] == 3){
				$where = "tp.cate_id=$cate_id";
			}else if($cateItem['level'] == 2){
				$where = "cate.parent_id=$cate_id or tp.cate_id=$cate_id";
			}else if($cateItem['level'] == 1){
				$cate2nd = db('category')->field('cate_id')->where("parent_id=$cate_id")->select();
				$cate2ndArr = [];
				foreach ($cate2nd as $key => $value) {
					$cate2ndArr[] = $value['cate_id'];
				}
				$where = "cate.parent_id in(".implode(",", $cate2ndArr).")";
			}
		}

		return db('topic')
		->alias('tp')
		->field('tp.topic_id,tp.topic_title,cate.cate_name,tp.price,tp.duration,t.tutor_name')
		->join('category cate', 'tp.cate_id=cate.cate_id')
		->join('tutor t', 't.tutor_id=tp.tutor_id')
		->where($where)
		->paginate(8,false,['query'=>['cate_id'=>$cate_id]]);
	}
	
}

?>