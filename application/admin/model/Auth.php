<?php

namespace app\admin\model;
use think\Db;
use think\Model;

class Auth extends Model{
	protected $pk = 'auth_id';

	protected $autoWriteTimestamp = true;

	protected static function init(){
		Auth::event('before_update',function($auth){
			if ($auth['pid'] == 0) {
				$auth['auth_c'] == '';
				$auth['auth_a'] == '';
			}
			// 根据pid查询对应的auth_name
			// $pid_auth_name = Db::name('auth')->where('auth_id',$auth['pid'])->value('auth_name');

			// if ($pid_auth_name == $auth['auth_name']) {
			// 	$error = $this->error('权限名称与父级权限一致');
			// }
		});
	}

	public function getSonsTree($data,$pid=0,$level=1){
		static $result = [];
		foreach ($data as $v) {
			if ($v['pid'] == $pid) {
				$v['level'] = $level;
				$result[] = $v;
				$this->getSonsTree($data,$v['auth_id'],$level+1);
			}
		}
		return $result;
	}
}