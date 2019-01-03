<?php

namespace app\home\model;
use think\Model;

class Goods extends Model{
	protected $pk = 'goods_id';

	protected $dateFormat = 'Y-m-d';

	public function getTypeGoods($type,$limit){
		$where = [
			'is_sale' => 1,
			'is_delete' => 0
		];
		if ($type == 'is_crazy') {
			return $this->where($where)->order('goods_price asc')->limit($limit)->select();
		} else {
			$where[$type] = 1;
			return $this->where($where)->limit($limit)->select();
		}
	}

	public function addGoodsHistory($goods_id){
		$history = cookie('history') ? cookie('history') : [] ;

		if ($history) {
			array_unshift($history,$goods_id);
			$history = array_unique($history);
			if ( count($history)>5 ) {
				array_pop($history);
			}
		} else {
			$history[] = $goods_id;
		}
		cookie('history',$history,3600*24*7);
		$where = [
			'is_sale' => 1,
			'is_delete' => 0,
			'goods_id' => ['in',$history]
		];
		$str = implode(',',$history);
		$history = $this->where($where)->order("field(goods_id,$str)")->select()->toArray();
		return $history;

		// if ($history && !empty($goods_id) && is_numeric($goods_id) &&  empty(session('goods_id')) ) {
		// 	array_unshift($history,$goods_id);
		// 	$history = array_unique($history);
		// 	if ( count($history)>5 ) {
		// 		array_pop($history);
		// 	}
		// } else {
		// 	if (!empty($goods_id) && is_numeric($goods_id) && empty(session('goods_id')) ) {
		// 		$history[] = $goods_id;	
		// 	}
			
		// }
		// cookie('history',$history,3600*24*7);
		// $where = [
		// 	'is_sale' => 1,
		// 	'is_delete' => 0,
		// 	'goods_id' => ['in',$history]
		// ];
		// $str = implode(',',$history);
		// if (!empty($history)) {
		// 	$history = $this->where($where)->order("field(goods_id,$str)")->select()->toArray();
		// } else {
		// 	$history = $this->where($where)->select()->toArray();
		// }
		
		
		// return $history;
	}
}