<?php

namespace app\admin\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';

	protected $autoWriteTimestamp = true;

	public function getSonsTree($data,$pid=0,$level=1){
		static $result = [];
		foreach($data as $v){
			if ($v['pid'] == $pid) {
				$v['level'] = $level;
				$result[$v['cat_id']] = $v;
				$this->getSonsTree($data,$v['cat_id'],$level+1);	
			}
		}
		return $result;
	}
}