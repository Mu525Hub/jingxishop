<?php

namespace app\admin\validate;
use think\Validate;

class Attribute extends Validate{
	protected $rule = [
		'attr_name' => 'require|unique:attribute',
		'type_id' => 'require',
		'attr_values' => 'require'
	];

	protected $message = [
		'attr_name.require' => '属性名称不能为空',
		'attr_name.unique' => '属性名已被注册',
		'type_id.require' => '请选择商品类型',
		'attr_values.require' => '属性值不能为空',
	];

	protected $scene = [
		'add' => ['attr_name','type_id','attr_values'],
		'upd' => ['attr_name','type_id','attr_values'],
		'checkAttrName' => ['auth_name','type_id']
	];
}