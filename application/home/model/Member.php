<?php

namespace app\home\model;
use think\Model;

class Member extends Model{
	protected $pk = 'member_id';

	protected $autoWriteTimestamp = true;

	public static function init(){
		Member::event('before_insert',function($member){
			$member['password'] = md5($member['password'].config('password_salt'));
		});
	}

	public function checkUser($username,$password){
		$where = [
			'username' => $username,
			'password' => md5($password.config('password_salt'))
		];
		$userInfo = $this->where($where)->find();
		if ($userInfo) {
			session('username',$userInfo['username']);
			session('member_id',$userInfo['member_id']);
			return true;
		} else {
			return false;
		}
	}
}