<?php

namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;

class LoginController extends Controller{
	public function login(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'User.login',[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			$userModel = new User();
			$status = $userModel->checkUser($postData['username'],$postData['password']);
			if ($status) {
				$this->redirect('/admin/index/index');
			} else {
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch();
	}

	public function logout(){
		session('user_id',null);
		session('username',null);
		$this->redirect('/admin/login/login');
	}
}