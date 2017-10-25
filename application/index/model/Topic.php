<?php
namespace app\index\model;
 

class Topic extends \think\Model {

	function getTopicList($tutor_id){
		//查询行家话题
		//$tutor_id = input('tutor_id')!=null?intval(input('tutor_id')):1;

		$topic_list = db('topic')
					->alias('tp')
					->join('compute_topic ctp','ctp.topic_id=tp.topic_id')
    				->field('tp.topic_id,tp.topic_title,tp.topic_content,tp.price,tp.create_time,tp.duration,ctp.avg_score,ctp.meet_number')
    				->where("tp.tutor_id='$tutor_id'")
    				->order('tp.topic_id desc')
    				->select();

    	return $topic_list;
	}

	public function getList($cateId='')
	{				
		$cate_id = input('cate_id')!=null?intval(input('cate_id')):0;
		$city_id = input('city_id')!=null?intval(input('city_id')):1;
		$requirement = input('requirement')!=null?input('requirement'):'';
		$order = 'topic_id';
		$where = '';

		if($city_id && $cateId){
			$where = "ct.city_id=$city_id  AND ";
		}else if($city_id){
			$where = "ct.city_id=$city_id";
		}		

		if($cateId){
			$cateItem = db('category')->field('cate_name,cate_id,level')->where("cate_id=$cateId")->find();
			if($cateItem['level'] == 3){
				$where .= "tp.cate_id=$cateId";
			}else if($cateItem['level'] == 2){
				$where .= "(cate.parent_id=$cateId or cate.cate_id=$cateId)";
			}else if($cateItem['level'] == 1){
				$cate2nd = db('category')->field('cate_id')->where("parent_id=$cateId")->select();
				$cate2ndArr = [];
				foreach ($cate2nd as $key => $value) {
					$cate2ndArr[] = $value['cate_id'];
				}
				
				$chkFlag = db('topic')
				->alias('tp')
				->field('tp.cate_id')
				->join('tutor t','t.tutor_id=tp.tutor_id')
				->join('city c','c.city_id=t.city_id')
				->where("t.city_id=$city_id")
				->select();
				foreach ($chkFlag as $key => $value) {
					$cate_get[] = $value['cate_id'];
				}				
				$where .= "(cate.parent_id in(".implode(",", $cate_get).") or cate.cate_id in(".implode(",", $cate_get)."))";
			}
		}

		$min = input('min');
		if(!$min){
			$min = 0;
		}else{
			$min = input('min');
		}		
        $max = input('max');

        $where_price = "";
        if ($max) {
        	$where_price = "price BETWEEN $min AND $max";
        }else if($min) {
        	$where_price = "price > $min";
        }

		$w_min = input('low-price');
		if(!$w_min || $w_min<0){
			$w_min = 0;
		}else{
			$w_min = input('low-price');
		}
		
        $w_max = input('high-price');
        if ($w_max) {
        	$where_price = "price BETWEEN $w_min AND $w_max";
        }     
        
        $order_sort='desc';		
		if($requirement){
			if ($requirement == 'price_asc') {
				$requirement = 'price';
				$order_sort = 'asc';
			}else if($requirement == 'price_desc') {
				$requirement = 'price';
			}
			$order = $requirement;
		}		

		return db('topic')
		->alias('tp')
		->field('tp.topic_id,tp.topic_title,
			tp.topic_content,tp.cate_id,
			tp.price,ct.city_id,ct.city_name, 
			tp.tutor_id,tt.tutor_identity,tt.tutor_img,tt.tutor_name,ctt.total_meet,ctp.avg_score')
		->join('category cate','tp.cate_id = cate.cate_id')
		->join('tutor tt','tp.tutor_id = tt.tutor_id')
		->join('city ct','tt.city_id = ct.city_id')
		->join('compute_tutor ctt','ctt.tutor_id = tt.tutor_id')
		->join('compute_topic ctp','ctp.topic_id = tp.topic_id')
		->where($where)
		->where($where_price)
		->order($order.' '.$order_sort)		
		->select();
		
	}
}

?>