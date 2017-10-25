<?php
namespace app\admin\controller;

class Comment extends \think\Controller
{
    public function index()
    {
        //查询用户列表
        $comment_list = model('Comment')->getCommentList();
        $this->assign('comment_list', $comment_list);

        $this->assign('user_nickname', input('user_nickname'));
        $this->assign('topic_id', input('topic_id'));

        return $this->fetch();
        
    }

    public function delete()
    {
       db("comment")->delete(input());
       $this->success('删除成功','index');
    }

}
