<?php
namespace app\index\model;
use \think\Session;

class User extends \think\Model {

	protected $updateTime = '';

	function getUserInfo(){
		$user_id = Session::get('info.user_id');
		return db('user')->where("user_id='$user_id'")->find();
	}

}

?>