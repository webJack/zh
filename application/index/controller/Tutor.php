<?php
namespace app\index\controller;
use \think\Controller;
use \think\Session;

//主界面控制器
class Tutor extends Controller
{
    public function index()
    {
    	//查询行家个人信息
    	$tutor_info = model('Tutor')->getTutor(input('tutor_id'));

    	//查询行家话题列表
    	$topic_list = model('Topic')->getTopicList(input('tutor_id'));

        //查询评论列表
        $comment_list = model('Comment')->getCommentList(input('tutor_id'));

        $user_id = Session::get('info.user_id');
        $tutor_id = input('tutor_id');

        //判断是否加入了心愿单
        $wish = db('wish')->where("tutor_id='$tutor_id' and user_id='$user_id'")->find();

    	$this->assign('tutor_info',$tutor_info);
    	$this->assign('topic_list',$topic_list);
        $this->assign('comment_list',$comment_list);
        $this->assign('wish',$wish);
        return $this->fetch();
    }

}
