<?php

namespace app\admin\model;
use think\Model;

class Attribute extends Model{
	protected $pk = 'attr_id';

	protected $autoWriteTimestamp = true;

	public static function init(){
		Attribute::event('before_update',function($attr){
			if ($attr['attr_input_type'] == 0) {
				$attr['attr_values'] = '';
			}
		});
	}
}