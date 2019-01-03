<?php

namespace app\admin\model;
use think\Model;
use app\admin\model\Role;

class User extends Model{
	protected $pk = 'user_id';

	protected $autoWriteTimestamp = true;

	//定义钩子的代码
	protected static function init(){
		User::event('before_insert',function($user){
			$user['password'] = md5($user['password'].config('password_salt'));
		});

		//编辑的前钩子 
		User::event('before_update',function($user){
			unset($user['repassword']);
			if ($user['password'] == '') {
				unset($user['password']);
			} else {
				$user['password'] = md5($user['password'].config('password_salt'));
			}
		});
	}

	//判断用户名或密码是否匹配
	public function checkUser($username,$password){
		$where = [
			'username' => $username,
			'password' => md5($password.config('password_salt'))
		];
		$userInfo = $this->where($where)->find();
		if ($userInfo) {
			session('user_id',$userInfo['user_id']);
			session('username',$userInfo['username']);
			$this->_initAuth($userInfo['role_id']);
			return true;
		} else {
			return false;
		}
	}

	private function _initAuth($role_id){
		$auth_ids_list = Role::where('role_id','=',$role_id)->value('auth_ids_list');
		if ($auth_ids_list == '*') {
			//超级管理员
			$authAll = Auth::select()->toArray();
		} else {
			$authAll = Auth::where('auth_id','in',$auth_ids_list)->select()->toArray();
		}
		//两个奇淫技巧
		$auths = [];
		foreach($authAll as $v){
			$auths[ $v['auth_id'] ] = $v;
		}
		$children = [];
		foreach($authAll as $v){
			$children[ $v['pid'] ][] = $v['auth_id'];
		}
		//保存到session中
		session('auths',$auths);
		session('children',$children);

		//权限防翻墙
		if ($auth_ids_list == '*') {
			$visitor = '*';
		} else {
			$visitor = [];
			foreach($authAll as $v){
				$visitor[] = strtolower($v['auth_c'].'/'.$v['auth_a']);
			}
		}
		session('visitor',$visitor);
	}
}