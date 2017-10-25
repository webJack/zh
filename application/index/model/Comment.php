<?php
namespace app\index\model;
use \think\Session;

class Comment extends \think\Model {

	function getCommentList($tutor_id){
        
        $comment_list = db('comment')
                    ->alias('c')
                    ->join('user u', 'c.user_id=u.user_id')
                    ->join('topic tp', 'tp.topic_id=c.topic_id')
                    ->join('tutor t', 't.tutor_id=tp.tutor_id')
                    ->join('reply r', 'r.comment_id=c.comment_id','left')
                    ->field('c.comment_id,c.comment_content,c.agree_number,r.reply_id,r.reply_content,c.create_time,u.user_nickname,u.user_img,tp.topic_id,tp.topic_title')
                    ->where("t.tutor_id='$tutor_id'")
                    ->paginate(2);

        return $comment_list;

	}
}

?>