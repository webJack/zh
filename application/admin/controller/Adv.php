<?php
namespace app\admin\controller;

class Adv extends \think\Controller
{
    public function index()
    {
        $adv_list = db('adv')
                    ->alias('a')
                    ->join('category c','c.cate_id=a.cate_id')
                    ->field('c.cate_name,a.*')
                    ->paginate(8);
        $this->assign('adv_list', $adv_list);

        return $this->fetch();
    }

    public function add()
    {
        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);

        //跳转到视图add.html
        return $this->fetch();
    }

    public function edit()
    {   
        $adv_info = db('adv')->find(input());
        $this->assign('adv_info', $adv_info);

        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);

        //跳转到视图add.html
        return $this->fetch();
    }

    public function update()
    {
        if(!empty($_FILES['adv_img']['tmp_name'])){
            $adv_img = 'index/index/img/'.saveAndgetImgSrc(ROOT_PATH."public/static/index/index/img/",'adv_img');
        }else{
            $adv_img = input('img');
        }
        if(!empty($_FILES['adv_bigimg']['tmp_name'])){
            $adv_bigimg = 'index/index/img/'.saveAndgetImgSrc(ROOT_PATH."public/static/index/index/img/",'adv_bigimg');
        }else{
            $adv_bigimg = input('bigimg');
        }
        db('adv')->where('adv_id='.input('adv_id'))->update([
            'adv_img'=>$adv_img,
            'adv_bigimg'=>$adv_bigimg,
            'cate_id'=>input('cate_id')
        ]);
        $this->success('更新成功','index');
    }   

    public function save()
    {
        $adv_img = saveAndgetImgSrc(ROOT_PATH."public/static/index/index/img/",'adv_img');
        $adv_bigimg = saveAndgetImgSrc(ROOT_PATH."public/static/index/index/img/",'adv_bigimg');
        db('adv')->insert([
            'adv_img'=>'index/index/img/'.$adv_img,
            'adv_bigimg'=>'index/index/img/'.$adv_bigimg,
            'cate_id'=>input('cate_id')
        ]);
        //成功跳转到index.html
        $this->success('添加成功', 'index');
    }

    public function delete()
    {
       db("adv")->delete(input());
       $this->success('删除成功','index');
    }

}
