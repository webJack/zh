<?php
namespace app\admin\controller;

class Tutor extends \think\Controller
{
    public function index()
    {
        $tutor_list = model('Tutor')->getTutorlist();
        $this->assign('tutor_list', $tutor_list);

        $city_list = db('city')->select();
        $this->assign('city_list',$city_list);

        $this->assign('tutor_name', input('tutor_name'));
        $this->assign('city_id', input('city_id'));

        return $this->fetch();
    }

    public function edit()
    {   
        $city_list = db('city')->select();
        $this->assign('city_list',$city_list);

        $tutor_info = model('Tutor')->getTutorInfo();
        $this->assign('tutor_info',$tutor_info);
        return $this->fetch();
    }

    public function delete()
    {
       db("tutor")->delete(input());
       $this->success('删除成功','index');
    }

    public function update()
    {
       model('Tutor')->editInfo();
       $this->success('更新成功','index');
    }    

}
