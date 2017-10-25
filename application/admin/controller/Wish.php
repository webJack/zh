<?php
namespace app\admin\controller;

class Wish extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $wish_list = model('Wish')->getWishList();
        $this->assign('wish_list', $wish_list);

        $this->assign('user_nickname', input('user_nickname'));
        $this->assign('tutor_name', input('tutor_name'));

        return $this->fetch();
        
    }

    public function delete()
    {
       db("wish")->delete(input());
       $this->success('删除成功','index');
    }

}
