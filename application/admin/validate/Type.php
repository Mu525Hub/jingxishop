<?php

namespace app\admin\validate;
use think\Validate;

class Type extends Validate{
	protected $rule = [
		'type_name' => 'require|unique:type'
	];

	protected $message = [
		'type_name.require' => '类型名称不能为空',
		'type_name.unique' => '类型名已被注册'
	];

	protected $scene = [
		'add' => ['type_name'],
		'upd' => ['type_name']
	];
}