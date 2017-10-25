<?php
namespace app\admin\controller;

class City extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $city_list = model('City')->getCityList();
        $this->assign('city_list', $city_list);

        $adv_list = db('adv')->select();
        $this->assign('adv_list', $adv_list);

        $this->assign('adv_id', input('adv_id'));

        return $this->fetch();
        
    }

    public function add()
    {
        $adv_list = db('adv')->select();
        $this->assign('adv_list', $adv_list);

        //跳转到视图add.html
        return $this->fetch();
    }

    public function edit()
    {   
        $adv_list = db('adv')->select();
        $this->assign('adv_list', $adv_list);

        $city_info = model('City')->getCityInfo();
        $this->assign('city_info', $city_info);

        //跳转到视图add.html
        return $this->fetch();
    }

    public function update()
    {
       db('city')->update(input());
       $this->success('更新成功','index');
    }   

    public function save()
    {
        db('city')->insert(input());
        //成功跳转到index.html
        $this->success('添加成功', 'index');
    }

    public function delete()
    {
       db("city")->delete(input());
       $this->success('删除成功','index');
    }

}
