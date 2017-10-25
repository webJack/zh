<?php
namespace app\index\model;
 

class Compute extends \think\Model {

	//更新话题统计表的评分
    function updateAvgScore(){
        $topic_id = input('topic_id');

        $avg_score = db('comment')->where("topic_id='$topic_id'")->value('avg(score)');

        db('compute_topic')->where("topic_id='$topic_id'")->setField('avg_score', $avg_score);
    }

    //更新话题统计表和行家统计表的约见人数
    function updateMeetNumber(){
        $topic_id = input('topic_id');
        $tutor_id = input('tutor_id');
        db('compute_topic')->where("topic_id='$topic_id'")->setInc('meet_number');
        db('compute_tutor')->where("tutor_id='$tutor_id'")->setInc('total_meet');
    }
}

?>