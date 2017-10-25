<?php
namespace app\index\controller;
use \think\Controller;

//主界面控制器
class Subject extends Controller
{
    public function index()
    {	
    	$adv_info = db('adv')->where('adv_id='.input('adv_id'))->find();

    	$city_id = input('city_id');

    	$cate_id = $adv_info['cate_id'];
		$where = '';

		$cate_info = db('category')->field('cate_name,cate_id,level')->where("cate_id='$cate_id'")->find();

		$where = "t.city_id='$city_id' and (c.parent_id='$cate_id' or tp.cate_id='$cate_id')";

    	$subject_list = db('tutor')
						->alias('t')
						->join('topic tp',"tp.tutor_id = t.tutor_id")
						->join('compute_tutor ct',"ct.tutor_id = t.tutor_id")
						->join('category c',"c.cate_id = tp.cate_id")
						->field('t.tutor_name,t.tutor_img,t.tutor_identity,tp.topic_title,ct.total_wish')
						->distinct(true)
						->where($where)	
						->select();

    	$this->assign('subject_list',$subject_list);
    	$this->assign('adv_bigimg',$adv_info['adv_bigimg']);
        return $this->fetch();
    }
}
