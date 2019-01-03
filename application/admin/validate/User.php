<?php

namespace app\admin\validate;
use think\Validate;

class User extends Validate{
	protected $rule = [
		'username' => 'require|unique:user',
		'password' => 'require',
		'role_id' => 'require',
		'repassword' => 'require|confirm:password',
		'captcha' => 'require|captcha'
	];

	protected $message = [
		'username.require' => '用户名不能为空',
		'username.unique' => '用户名已被注册',
		'password.require' => '密码不能为空',
		'role_id.require' => '请选择角色',
		'repassword.require' => '确认密码不能为空',
		'repassword.confirm' => '两次输入的密码不一致',
		'captcha.require' => '验证码不能为空',
		'captcha.captcha' => '验证码输入有误'
	];

	protected $scene = [
		'add' => ['role_id','username','password','repassword'],
		'upd' => ['password','repassword','role_id'],
		'login' => ['username'=>'require','password'=>'require','captcha']
	];
}