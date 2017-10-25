<?php
namespace app\admin\controller;

class Category extends \think\Controller
{
    public function index()
    {
        //查询列表
        $cate_list = model('Category')->getCateList();

        $this->assign('cate_list', $cate_list);

        $this->assign('level',input('level'));

        //跳转到视图index.html
        return $this->fetch();
        
    }

    public function add()
    {
        //查询2级以上分类
        $cate_list = model('Category')->getParentCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);
        //跳转到视图add.html
        return $this->fetch();
    }

    public function delete()
    {   
       db("category")->delete(input());
       $this->success("删除成功",'index');
    }

    public function save()
    {
        model('Category')->saveInfo();
        //成功跳转到index.html
        $this->success('添加成功', 'index');
    }
}
