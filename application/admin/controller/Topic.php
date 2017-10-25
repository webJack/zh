<?php
namespace app\admin\controller;

class Topic extends \think\Controller
{
    public function index()
    {   
        //查询列表
        $topic_list = model('Topic')->getCateList();

        $this->assign('topic_list', $topic_list);

        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);

        $this->assign('cate_id',input('cate_id'));

        //跳转到视图index.html
        return $this->fetch();
        
    }

    public function edit()
    {
        $topic_info = db('topic')->where("topic_id=".input('cate_id'))->find();
        $this->assign('topic_info', $topic_info);
       
        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);
               
        //成功跳转到edit.html
        return $this->fetch();
    }

    public function update()
    {   
        
        db('topic')->update(input());
        //成功跳转到index.html
        $this->success('修改成功', 'index');
    }

    public function delete()
    {
        db('topic')->delete(input());

        db('compute_topic')->where('topic_id='.input('topic_id'))->delete();
        $this->success("删除成功","index");
    }
}
