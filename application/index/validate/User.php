<?php
namespace app\index\validate;
use \think\Validate;

class User extends Validate
{	
    protected $rule = [
        'user_realname' => 'require',
        'city_id' => 'require',
        'user_introduction' => 'require'
    ];

    protected $message = [
        'user_realname.require' => '真实姓名不为空',
        'user_introduction.require' => '你还没有填写个人简介哦',
        'city_id.require' => '请选择城市'
    ];



}
