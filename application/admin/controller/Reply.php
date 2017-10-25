<?php
namespace app\admin\controller;

class Reply extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $reply_list = db('reply')->paginate(8);
        $this->assign('reply_list', $reply_list);

        return $this->fetch();
        
    }

    public function delete()
    {
       db("reply")->delete(input());
       $this->success('删除成功','index');
    }

}
