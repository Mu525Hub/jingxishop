<?php

namespace app\home\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';

	public function getNavCat(){
		$where = [
			'is_show' => 1,
			'pid' => 0
		];
		return $this->where($where)->select();
	}

	//递归找祖先
	public function getFamilyCats($data,$cat_id){
		static $result = [];
		foreach($data as $v){
			if ($v['cat_id'] == $cat_id) {
				$result[] = $v;
				$this->getFamilyCats($data,$v['pid']);
			}
		}
		return array_reverse($result);
	}

	//递归找子孙
	public function getSonsCats($data,$cat_id){
		static $result = [];
		foreach($data as $v){
			if ($v['pid'] == $cat_id) {
				$result[] = $v['cat_id'];
				$this->getSonsCats($data,$v['cat_id']);
			}
		}

		return $result;
	}
}