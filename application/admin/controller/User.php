<?php
namespace app\admin\controller;

class User extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $user_list = model('User')->getUserlist();
        $this->assign('user_list', $user_list);

        $city_list = db('city')->select();
        $this->assign('city_list',$city_list);

        $this->assign('user_nickname', input('user_nickname'));
        $this->assign('user_realname', input('user_realname'));
        $this->assign('city_id', input('city_id'));
        $this->assign('user_phone', input('user_phone'));

        return $this->fetch();
        
    }

    public function add()
    {
        $city_list = db('city')->select();
        $this->assign('city_list',$city_list);
       
        //跳转到视图add.html
        return $this->fetch();
    }

    public function edit()
    {   
        $city_list = db('city')->select();
        $this->assign('city_list',$city_list);

        $user_info = model('user')->getUserInfo();
        $this->assign('user_info',$user_info);
        return $this->fetch();
    }

    public function delete()
    {
       db("user")->delete(input());
       $this->success('删除成功','index');
    }

    public function update()
    {
       model('User')->editInfo();
       $this->success('更新成功','index');
    }    

    public function save()
    {
        model('User')->saveInfo();

        $this->success('添加成功', 'index');
    }

    public function toBeTheSpecial()
    {       
        //db('user')->where("user_id=".input('user_id'))->update(['status'=>3]);

        $user_info = db('verify')->where("user_id=".input('user_id'))->find();

        //$user_info = model('user')->getUserInfo();
        $this->assign('user_info', $user_info);        
        return $this->fetch();
    }

    public function pass()
    {       
        db('user')->where("user_id=".input('user_id'))->update(['status'=>3]);

        $tutor_id = model('User')->addTutor();
        model('User')->addTopic($tutor_id);
        //$user_info = model('user')->getUserInfo();
       // $this->assign('user_info', $user_info);        
        //return $this->fetch();
       $this->success('审核通过！！','index');
    }    

    public function reject()
    {
        db('user')->where("user_id=".input('user_id'))->update(['status'=>1]);
        $the_reason = input('reason')?input('reason'):"您的申请没有通过!!!";
        $this->error('审核不通过！！','index');
    }

}
