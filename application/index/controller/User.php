<?php
namespace app\index\controller;
use \think\Controller;
use \think\captcha\Captcha;
use \think\Session;
use \think\Request;

//用户控制器
class User extends Controller
{	
	//行为，对应一个html页面
    public function index()
    {
        //获取订单列表
        $order_list = model('Order')->getOrderList();

        $this->assign('order_list',$order_list);

        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        //转到视图
        return $this->fetch(); 
    }

    //登陆界面
    public function signIn()
    {
        //绑定数据
        return $this->fetch();
    }

    //登陆操作
    public function doSignIn(){
        $user_phone = input("user_phone");
        $user_pwd = input("user_pwd");
        $user_check = input('user_check');

        // find查询一条记录
        $info = db('user')->where("user_phone='$user_phone' and user_pwd='$user_pwd'")->find();

        if(!$info){
            return '手机号或密码错误！';
        }

        Session::set('info', $info);

        $status_tutor = db('tutor_info')->where("user_id=".Session::get('info.user_id'))->value('status');
        $status_topic = db('tutor_topic')->where("user_id=".Session::get('info.user_id'))->value('status');

        //判断申请行家状态
        if($status_tutor == 0){
            Session::set('material', 'true');
        }else if($status_topic == 0){
            Session::set('topic', 'true');
        }else if($status_tutor == 1 & $status_topic == 1){
            Session::set('verify', 'true');
        }else if(db('verify')->where("user_id=".Session::get('info.user_id'))->find()){
            Session::set('zhima', 'true');
        }

        //判断该用户是否为行家
        $user_id = $info['user_id'];
        $tutor_info = db('tutor')
                ->where("user_id='$user_id'")
                ->find();

        if($tutor_info){
            Session::set('tutor_info', $tutor_info);
        }
    }

    //生成验证码
    public function setCaptcha(){
        $captcha = new Captcha();
        // 没有混淆线
        $captcha->useCurve = false;
        // 生成
        return $captcha->entry();
    }

    //注册界面
    public function signUp()
    {
        //绑定数据
        return $this->fetch();
    }

    //注册操作
    public function doSignUp(){
        $user_phone = input("user_phone");
        $user_check = input('user_check');
        $user_phoneCheck = input('user_phoneCheck');
        $user_nickname = input("user_nickname");
        $user_pwd = input("user_pwd");

        $info = db('user')
                ->where("user_phone='$user_phone'")
                ->find();

        $cap = db('captcha')->where("phone='$user_phone'")->find();

        if(!$cap || $cap['code'] != $user_phoneCheck){
            return ['phoneCheck' => '手机验证码输入不正确！'];
        }

        if($info){
            return ['info' => '该用户已存在！'];
        }

        //删除验证码
        db('captcha')->where("phone='$user_phone'")->delete();

        $data['user_phone'] = $user_phone;
        $data['user_nickname'] = $user_nickname;
        $data['user_pwd'] = $user_pwd;
        $data['user_img'] = 'index/user/img/default_avatar.jpg';

        model('User')->save($data);
    }

    //发送手机验证码
    function sendCaptcha(){
        $user_phone = input("user_phone");
        $code = rand(1000,9999);
        $user_check = input('user_check');

        $info = db('user')
                ->where("user_phone='$user_phone'")
                ->find();
        
        $captcha = new Captcha();
        //print_r($captcha);
        if(!$captcha->check($user_check)){
            return ['check' => '请填写正确的验证码，看不清？点击图片刷新！'];
        }

        if($info){
            return ['info' => '该手机已注册，请直接登录！'];
        }

        if(db('captcha')->where("phone='$user_phone'")->find()){
            db('captcha')->where("phone='$user_phone'")->setField('code', $code);
        }else{
            db('captcha')->insert(['phone'=>$user_phone,'code'=>$code]);
        }
    }

    //忘记密码操作
    public function reset()
    {
        //绑定数据
        return $this->fetch();
    }

    //心愿单
    public function wishlist(){
        //获取心愿单列表
        $wish_info = model('Wish')->getWishList();
        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);
        $this->assign('wish_list',$wish_info['wish_list']);
        $this->assign('topic_list',$wish_info['topic_list']);
        return $this->fetch();
    }

    //加入心愿单
    public function addWish(){
        $data['user_id'] = Session::get('info.user_id');

        $data['tutor_id'] = input('tutor_id');

        db('wish')->insert($data);
        
        db('compute_tutor')->where("tutor_id=".$data['tutor_id'])->setInc('total_wish');
    }

    //移除心愿单
    public function deleteWish(){
        $user_id = Session::get('info.user_id');

        $tutor_id = input('tutor_id');

        db('wish')->where("user_id='$user_id' and tutor_id='$tutor_id'")->delete();

        db('compute_tutor')->where("tutor_id='$tutor_id'")->setDec('total_wish');
    }

    //预约单
    public function bookingList(){
        //获取预约单列表
        $booking_list = model('Order')->getBookingList();

        $this->assign('booking_list',$booking_list);

        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        return $this->fetch();
    }

    //预约行家
    public function booking(){
        $data['user_id'] = Session::get('info.user_id');
        $data['tutor_id'] = input('tutor_id');
        $data['topic_id'] = input('topic_id');
        $data['question'] = input('question');
        $data['description'] = input('description');
        $data['create_time'] = time();

        db('order')->insert($data);
    }

    //预约单状态改变
    function bookingStatus(){
        $order_id = input('order_id');
        $status = input('status');

        db('order')->where("order_id='$order_id'")->setField('status', $status);
    }

    //话题列表
    public function topicList(){
        //获取话题列表
        $topic_list = model('Topic')->getTopicList(Session::get('tutor_info.tutor_id'));

        $this->assign('topic_list',$topic_list);

        //查询分类
        $cate_list = model('Category')->getAllCate();

        //得到分类树
        $cate_list = getCateTree($cate_list);

        $this->assign('cate_list', $cate_list);

        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        return $this->fetch();
    }

    //添加话题
    public function addTopic(){
        $data['tutor_id'] = input('tutor_id');
        $data['topic_title'] = input('topic_title');
        $data['price'] = input('price');
        $data['duration'] = input('duration');
        $data['cate_id'] = input('cate_id');
        $data['topic_content'] = input('topic_content').input('topic_achievement').input('summary');
        $data['create_time'] = time();

        db('topic')->insert($data);
        $topic_id = db('topic')->getLastInsID();

        //新增一条话题统计数据
        db('compute_topic')->insert(['topic_id'=>$topic_id,'tutor_id'=>Session::get('tutor_info.tutor_id')]); //?
    }

    //评论列表
    public function commentList(){
        //获取评论列表
        $comment_list = model('Comment')->getCommentList(Session::get('tutor_info.tutor_id'));

        $this->assign('comment_list',$comment_list);

        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        return $this->fetch();
    }

    //学员付款
    public function pay(){
        $this->bookingStatus(); 
        model('Compute')->updateMeetNumber();
    }

    //学员评论
    public function addComment(){
        $data['user_id'] = Session::get('info.user_id');
        $data['topic_id'] = input('topic_id');
        $data['comment_content'] = input('comment_content');
        $data['score'] = input('score');
        $data['create_time'] = time();

        db('comment')->insert($data);

        model('Compute')->updateAvgScore();
        $this->bookingStatus(); 
    }

    //行家回复
    public function addReply(){
        $data['comment_id'] = input('comment_id');
        $data['reply_content'] = input('reply_content');

        db('reply')->insert($data);
    }


    public function coupon(){
        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);
        return $this->fetch();
    }

    //个人资料
    public function profile(){
        //登陆之后
        $info = model('User')->getUserInfo();

        $city = db('city')->select();

        $this->assign('user_info',$info);

        $this->assign('city',$city);

        $show = input('show');

        $this->assign('show',$show);

        return $this->fetch();
    }

    //编辑个人资料
    public function editProfile(){
        $user_id = Session::get('info.user_id');
        $data['user_nickname'] = input('user_nickname');
        $data['user_realname'] = input('user_realname');
        $data['city_id'] = input('city_id');
        $data['user_introduction'] = input('user_introduction');

        db('user')
            ->where("user_id='$user_id'")
            ->update($data);
    }

    //头像设置
    public function avatar(){
        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        $show = input('show');

        $this->assign('show',$show);
        return $this->fetch();
    }

    //编辑头像
    function editAvatar(){
        $user_id = Session::get('info.user_id');
        $name = saveAndgetImgSrc(ROOT_PATH."public/static/index/user/img/",'user_img');
        db('user')->where("user_id='$user_id'")->setField('user_img','index/user/img/'.$name);
    }

    //验证手机
    public function mobile(){

        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        $show = input('show');

        $this->assign('show',$show);

        return $this->fetch();
    }

    //发送手机验证码
    function sendMobileCap(){
        $user_phone = input("user_phone");
        $code = rand(1000,9999);
        $info = db('user')
                ->where("user_phone='$user_phone'")
                ->find();
        if($info){
            return ['info' => '这个号码已经验证过了！'];
        }

        if(db('captcha')->where("phone='$user_phone'")->find()){
            db('captcha')->where("phone='$user_phone'")->setField('code', $code);
        }else{
            db('captcha')->insert(['phone'=>$user_phone,'code'=>$code]);
        }
    }

    //编辑手机号码
    function editMobile(){
        $user_id = Session::get('info.user_id');
        $user_phone = input('user_phone');
        $code = input('code');

        $cap = db('captcha')->where("phone='$user_phone'")->find();

        if(!$cap || $cap['code'] != $code){
            return ['code' => '手机验证码输入不正确！'];
        }

        db('user')
            ->where("user_id='$user_id'")
            ->update(['user_phone' => $user_phone]);

        //删除验证码
        db('captcha')->where("phone='$user_phone'")->delete();

    }

    //设置密码
    public function password(){
        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);

        $show = input('show');

        $this->assign('show',$show);
        return $this->fetch();
    }

    //编辑密码
    function editPwd(){
        $user_id = Session::get('info.user_id');
        $user_pwd = input('user_pwd');
        db('user')
            ->where("user_id='$user_id'")
            ->update(['user_pwd' => $user_pwd]); 
    }

    public function receipt_accounts(){
        $info = model('User')->getUserInfo();

        $this->assign('user_info',$info);
        
        return $this->fetch();
    }
 
}
