<?php
namespace app\index\model;
 

class Category extends \think\Model {

    public function getParentCate($cur_id){
        return db('category')
        // ->field('cate_id,cate_name,parent_id,level')
        ->where('cate_id='.$cur_id)
        ->find();
    }

    public function getThirdCate($cur_id){
        return db('category')
        ->field('cate_id,cate_name,parent_id,level')
        ->where('parent_id='.$cur_id)
        ->where('level=3')
        ->select();
    }

    public function getAllCate()
    {
        return db('category')
        ->field('cate_id,cate_name,parent_id,level')
        // ->order('level')
        ->select();
    }

    public function getCurCateInfo($cur_id){
        return db('category')
            ->where('cate_id='.$cur_id)
            ->find();
    }

    public function getActive($min,$max=0)
    {
        $active_class = [];
        $min = $min?$min:0;
        if ($min==0 && $max==300) {
            $active_class [0] = 'active';
        }else{
            $active_class [0] = '';
        }
        if($min==301 && $max==600){
            $active_class [1] = 'active';
        }else{
            $active_class [1] = '';
        } 

        if($min==601 && $max==1000){
            $active_class [2] = 'active';
        }else{
            $active_class [2] = '';
        } 

        if($min>1000){
            $active_class [3] = 'active';
        }else{
            $active_class [3] = '';
        }

        return $active_class;
    }

    public function getPicked($picked)
    {
        $picked_class =[];
        if ($picked=='all') {
            $picked_class[0]='picked';
        }else{
            $picked_class[0]='';
        }

        if ($picked=='meet') {
            $picked_class[1]='picked';
        }else{
            $picked_class[1]='';
        }       

        if ($picked=='avg_score') {
            $picked_class[2]='picked';
        }else{
            $picked_class[2]='';
        }       

        if ($picked=='create_time') {
            $picked_class[3]='picked';
        }else{
            $picked_class[3]='';
        }       

        if ($picked=='price') {
            $picked_class[4]='picked';
        }else if ($picked=='price_asc') {
            $picked_class[4]='picked';
        }else if ($picked=='price_desc') {
            $picked_class[4]='picked';
        }else{
            $picked_class[4]='';
        }

        return $picked_class;
    }

}



?>