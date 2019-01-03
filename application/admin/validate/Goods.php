<?php

namespace app\admin\validate;
use think\Validate;

class Goods extends Validate{
	protected $rule = [
		'goods_name' => 'require|unique:goods',
		'goods_price' => 'require|gt:0',
		'goods_number' => 'require|regex:^\d+$|egt:0',
		'cat_id' => 'require'
	];

	protected $message = [
		'goods_name.require' => '商品名称不能为空',
		'goods_name.unique' => '商品名称不能重复',
		'goods_price.require' => '商品价格不能为空',
		'goods_price.gt' => '商品价格必须大于0',
		'cat_id.require' => '请选择分类',
		'goods_number.require' => '商品库存不能为空',
		'goods_number.regex' => '商品库存必须为纯数字',
		'goods_number.egt' => '商品库存必须大于等于0'
	];

	protected $scene = [
		'add' => ['goods_name','goods_price','goods_number','cat_id']
	];
}
