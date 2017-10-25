<?php
namespace app\index\controller;
use \think\Controller;
use \think\Session;

//主界面控制器
class Betutor extends Controller
{
    public function index()
    {
    	//print_r(Session::get('info.user_id'));exit();
        return $this->fetch();
    }

    public function wait()
    {
        return $this->fetch();
    }

    public function zhimaCheck(){
        $data['realname'] = input('realname');
        $data['ID'] = input('ID');
        $data['user_id'] = Session::get('info.user_id');
        db('verify')->insert($data);

        db('user')->where("user_id=".$data['user_id'])->setField('user_realname', $data['realname']);
    }

    public function zhima(){
        $user_verify = db('verify')->where('user_id='.Session::get('info.user_id'))->find();

        $this->assign('user_verify',$user_verify);
        return $this->fetch();
    }

    //发送手机验证码
    function sendMobileCap(){
        $user_phone = input("user_phone");
        $code = rand(1000,9999);

        if(db('captcha')->where("phone='$user_phone'")->find()){
            db('captcha')->where("phone='$user_phone'")->setField('code', $code);
        }else{
            db('captcha')->insert(['phone'=>$user_phone,'code'=>$code]);
        }
    }

    //编辑手机号码
    function editMobile(){
        $user_phone = input('user_phone');
        $code = input('code');

        $cap = db('captcha')->where("phone='$user_phone'")->find();

        if(!$cap || $cap['code'] != $code){
            return ['code' => '手机验证码输入不正确！'];
        }

        //删除验证码
        db('captcha')->where("phone='$user_phone'")->delete();

        //更新session
        Session::set('zhima', 'true');

    }

    public function material()
    {
    	$user_id = Session::get('info.user_id');

        //查询资料
        $tutor_material = db('tutor_info')
                    ->where("user_id='$user_id'")
                    ->find();

        $city = db('city')->select();

        $category = db('category')->where('level=1')->select();

        $this->assign('tutor_material',$tutor_material);
        $this->assign('city',$city);
        $this->assign('category',$category);

        return $this->fetch();
    }

    public function addMaterial()
    {   
        $data = $_POST;
        $data['user_id'] = Session::get('info.user_id');

        if(input('isAvatar')){
            $name = saveAndgetImgSrc(ROOT_PATH."public/static/index/betutor/img/",'avatar');
            $data['avatar'] = 'index/betutor/img/'.$name;
        }
        unset($data['isAvatar']);
        if(db('tutor_info')->where("user_id=".$data['user_id'])->find()){
            db('tutor_info')->where("user_id=".$data['user_id'])->update($data);
        }else{
            db('tutor_info')->insert($data);
        }

        //更新session
        Session::set('material', 'true');
    }

    public function topic()
    {
    	$user_id = Session::get('info.user_id');

        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);

        //查询资料
        $tutor_topic = db('tutor_topic')
                    ->where("user_id='$user_id'")
                    ->find();
        $this->assign('tutor_topic',$tutor_topic);
        return $this->fetch();
    }

    public function saveTopic()
    {   
        $data = $_POST;
        $data['user_id'] = Session::get('info.user_id');

        if(db('tutor_topic')->where("user_id=".$data['user_id'])->find()){
            db('tutor_topic')->where("user_id=".$data['user_id'])->update($data);
        }else{
            db('tutor_topic')->insert($data);
        }

        Session::set('topic', 'true');
    }

    public function submit()
    {   
        $this->saveTopic();
        $user_id = Session::get('info.user_id');
        db('user')->where("user_id='$user_id'")->setField('status',2);

        db('tutor_info')->where("user_id='$user_id'")->setField('status',1);
        db('tutor_topic')->where("user_id='$user_id'")->setField('status',1);
        Session::set('verify', 'true');
    }

    public function save(){

        //db('tutor_info')->insert($data);
    }


    


}
