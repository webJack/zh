<?php
namespace app\index\controller;
use \think\Controller;

//主界面控制器
class Category extends Controller
{
    public function index()
    {   

        // 获取当前点击的分类信息 ()
        // 如果当前分类是第一级，要获取它的所有下级
        // 如果当前分类是第二级，要获取它的父级，找到它的所有下级
        $cur_category_id = input('cate_id')==null?1:input('cate_id');
        $cur_category_info = model('category')->getCurCateInfo($cur_category_id); 
        $this->assign('cur_category_info', $cur_category_info);
        if($cur_category_info['level'] == 1){
            //查询分类
            $cate_list = model('category')->getAllCate();

            //得到分类树
            $cate_list = getCateTree($cate_list);

            $this->assign('cate_list', $cate_list);
                 
        }else if($cur_category_info['level'] == 2){
            //获取父级分类
            $cur_parent_cate = model('category')->getParentCate(
            $cur_category_info['parent_id']);
            $this->assign('cur_parent_cate',$cur_parent_cate);          

            //获取子级分类
            $cur_child_cate = model('category')->getThirdCate(input('cate_id'));
            $this->assign('cur_child_cate',$cur_child_cate);
        }else if($cur_category_info['level'] == 3){
            //获取到当前三级的父级
            $cur_parent_cate = model('category')->getParentCate(
            $cur_category_info['parent_id']);
            $this->assign('cur_parent_cate',$cur_parent_cate);
             
            $cur_gran_cate = model('category')->getParentCate(
            $cur_parent_cate['parent_id']);
            $this->assign('cur_gran_cate',$cur_gran_cate);
        }
        
        //查询列表 
        //topic content
        $content_list = model('topic')->getList(input('cate_id'));
        // $this->assign('content_list', $content_list);        
        
        //city category
        $cur_city_info = model('city')->getCurCity(input('city_id'));
        $city_list = model('city')->getCityList();

        $this->assign('cur_city_info', $cur_city_info);
        $this->assign('city_list', $city_list);

        //get requiement
        $requirement = input('requirement');
        $picked_class = model('category')->getPicked($requirement);
        $this->assign('requirement', $requirement);
        $this->assign('picked_class', $picked_class);

        //get page
        $get_page_list = getPage($content_list);
        $cur_page = input('cur_page')==null?0:input('cur_page');
        $this->assign('get_page_list', $get_page_list);
        $this->assign('get_page_list_inner', $get_page_list['page_list'][$cur_page]);

        //筛选
        $min = input('min')?input('min'):input('low-price');
        $max = input('max')?input('max'):input('high-price');
        $active_class = model('category')->getActive($min,$max);
        $low_price = input('low-price');
        $high_price = input('high-price');
        $this->assign("low_price",$low_price);
        $this->assign("high_price",$high_price);
        $this->assign("min",$min);
        $this->assign("max",$max);
        $this->assign("active_class",$active_class);
        // exit();
        //跳转到视图index.html
        return $this->fetch();
    }

}