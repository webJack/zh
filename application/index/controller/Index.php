<?php
namespace app\index\controller;
use \think\Controller;
use \think\Session;

//主界面控制器
class Index extends Controller
{
    public function index()
    {
        if(input('del')){
            Session::delete('info');
            Session::delete('topic');
            Session::delete('material');
            Session::delete('zhima');
            Session::delete('tutor_info');
        }

    	//获取城市
    	$city = db('city')->select();
        $this->assign('city',$city);

        //获取当前城市
        $city_id = input('city_id')!=null?input('city_id'):1;

        $city_info = db('city')
                    ->alias('c')
                    ->join('adv a','a.adv_id=c.adv_id')
                    ->field('c.city_id,c.city_name,a.adv_id,a.adv_img')
                    ->where("c.city_id='$city_id'")
                    ->find();
        $this->assign('city_info',$city_info);

        //获取行家列表
        $tutor_list = model('Tutor')->getTutorList();
        $this->assign('tutor_list',$tutor_list);

        return $this->fetch();
    }
}
