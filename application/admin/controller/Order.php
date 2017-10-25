<?php
namespace app\admin\controller;

class Order extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $order_list = model('Order')->getOrderList();
        $this->assign('order_list', $order_list);

        $this->assign('user_nickname', input('user_nickname'));
        $this->assign('tutor_name', input('tutor_name'));
        $this->assign('topic_id', input('topic_id'));

        return $this->fetch();
        
    }

    public function delete()
    {
       db("order")->delete(input());
       $this->success('删除成功','index');
    }

}
