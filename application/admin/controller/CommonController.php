<?php

namespace app\admin\controller;
use think\Controller;

class CommonController extends Controller{
	public function _initialize(){
		if (!session('user_id')) {
			$this->error('请先登陆后再操作',url('/admin/login/login'));
		} else {
			$now_c = request()->controller();
			$now_a = request()->action();
			$now_ca = strtolower($now_c.'/'.$now_a);
			$visitor = session('visitor');
			//如果是超级管理员直接放行
			if ($visitor == '*' || strtolower( $now_c ) == 'index') {
				return;
			}
			//否则判断是否有访问权限
			if (!in_array($now_ca, $visitor)) {
				$this->error('敢翻墙,信不信扣你年终奖');
			}
		}
	}
}