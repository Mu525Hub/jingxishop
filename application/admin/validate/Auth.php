<?php

namespace app\admin\validate;
use think\Validate;

class Auth extends Validate{
	protected $rule = [
		'auth_name' => 'require|unique:auth',
		'pid' => 'require',
		'auth_c' => 'require',
		'auth_a' => 'require'
	];

	protected $message = [
		'auth_name.require' => '权限名称不能为空',
		'auth_name.unique' => '权限名已被注册',
		'pid.require' => '请选择一个父级权限',
		'auth_c.require' => '非顶级控制器名不能为空',
		'auth_a.require' => '非顶级方法名不能为空'
	];

	protected $scene = [
		'add' => ['auth_name','pid','auth_c','auth_a'],
		'checkAuthName' => ['auth_name']
	];
}