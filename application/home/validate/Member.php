<?php

namespace app\home\validate;
use think\Validate;

class Member extends Validate{
	protected $rule = [
		'username' => 'require|unique:member',
		'email' => 'require|email|unique:member',
		'password' => 'require',
		'repassword' => 'require|confirm:password',
		'phone' => 'require|regex:^1[3-8]\d{9}$|unique:member',
		'captcha' => 'require|captcha:1'
	];

	protected $message = [
		'username.require' => '用户名不能为空',
		'username.unique' => '用户名已被注册',
		'email.require' => '邮箱不能为空',
		'email.email' => '邮箱地址不正确',
		'email.unique' => '邮箱名已被注册',
		'password.require' => '密码不能为空',
		'repassword.require' => '确认密码不能为空',
		'repassword.confirm' => '两次密码不一致',
		'phone.require' => '手机号不能为空',
		'phone.regax' => '手机号码只能是数字',
		'phone.unique' => '手机号已被注册',
		'captcha.require' => '验证码不能为空',
		'captcha.captcha' => '验证码有误'
	];

	protected $scene = [
		'register' => ['username','email','password','repassword','phone'],
		'login' => ['username'=>'require','password','captcha']
	];
}