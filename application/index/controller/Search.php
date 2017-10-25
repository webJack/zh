<?php
namespace app\index\controller;
use \think\Controller;
use \think\Session;

//主界面控制器
class Search extends Controller
{
    public function index()
    {
        $cur_city_info = model('city')->getCurCity(input('city_id'));
        $city_list = model('city')->getCityList();

        $this->assign('cur_city_info', $cur_city_info);
        $this->assign('city_list', $city_list);

        $keywords = input('word');
        $city_id = input('city_id');
        $page = input('page');

        $this->assign('city_id', $city_id);
        $this->assign('keywords', $keywords);
        
        $where = "t.city_id=$city_id and (tp.topic_title like '%$keywords%' or t.tutor_name like '%$keywords%')";

        $pageParam = [
            'page'=>$page,
            'query'=>['city_id'=>$city_id,'word'=>$keywords]
        ];

        $content_list = db('topic')
                        ->alias('tp') 
                        ->join('tutor t','tp.tutor_id = t.tutor_id')
                        ->join('compute_tutor ct','ct.tutor_id = t.tutor_id')
                        ->join('compute_topic ctp','ctp.topic_id = tp.topic_id')
                        ->field('tp.topic_id,tp.topic_title,tp.price,t.tutor_id,t.tutor_identity,t.tutor_img,t.tutor_name,ct.total_meet,ctp.avg_score')
                        ->where($where)    
                        ->paginate(5,false,$pageParam);

        $this->assign('content_list', $content_list);

        $total_page = $content_list->lastPage();
        $cur_page = $content_list->currentPage();

        $this->assign('total_page', $total_page);
        $this->assign('cur_page', $cur_page);

        return $this->fetch();
    }
}
