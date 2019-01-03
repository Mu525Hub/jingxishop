<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class Goods extends Model{
	protected $pk = 'goods_id';

	protected $autoWriteTimestamp = true;

	public static function init(){
		Goods::event('before_insert',function($goods){
			//添加商品需要自动生成一个唯一的货号
			$goods['goods_sn'] = 'sn_'.time().uniqid();
		});

		//入库的后钩子
		Goods::event('after_insert',function($goods){
			$goods_id = $goods['goods_id'];
			$postData = input('post.');
			$attrValue = $postData['attrValue'];
			$attrPrice = $postData['attrPrice'];
			foreach($attrValue as $attr_id => $attr_values){
				if (is_array($attr_values)) {
					//单选属性
					foreach($attr_values as $k => $single_attr_value){
						$data = [
							'goods_id' => $goods_id,
							'attr_id' => $attr_id,
							'attr_value' => $single_attr_value,
							'attr_price' => $attrPrice[$attr_id][$k],
							'create_time' => time(),
							'update_time' => time()
						];
						Db::name('goods_attr')->insert($data);
					}
				} else {
					//唯一属性
					$data = [
						'goods_id' => $goods_id,
						'attr_id' => $attr_id,
						'attr_value' => $attr_values,
						'create_time' => time(),
						'update_time' => time()
					];
					Db::name('goods_attr')->insert($data);
				}
			}
		});
	}
}