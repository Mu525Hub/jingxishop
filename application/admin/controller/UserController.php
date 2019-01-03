<?php

namespace app\admin\controller;
use app\admin\model\User;
use app\admin\model\Role;

class UserController extends CommonController{
	public function upd(){
		if (request()->isPost()) {
			$postData = input('post.');
			if ($postData['password'] != '' || $postData['repassword'] != '' || $postData['role_id'] == '') {
				$result = $this->validate($postData,'User.upd',[],true);
				if ($result !== true) {
					$this->error(implode(',',$result));
				}
			}
			$userModel = new User();
			if ($userModel->update($postData)) {
				$this->success('修改成功',url('/admin/user/index'));
			} else {
				$this->error('修改失败');
			}
		}

		$user_id = input('user_id');
		$roles = Role::select();
		$userData = User::find($user_id);
		return $this->fetch('',[
			'userData' => $userData,
			'roles' => $roles
		]);
	}

	public function del(){
		$user_id = input('user_id');
		if (User::destroy($user_id)) {
			$this->success('删除成功','/admin/user/index');
		} else {
			$this->error('删除失败');
		}
	}

	public function index(){

		$userModel = new User();
		$userData = $userModel
			->alias('t1')
			->field('t1.*,t2.role_name')
			->join('sh_role t2','t1.role_id = t2.role_id','left')
			->order('user_id desc')
			->paginate(3);
		return $this->fetch('',[
			'userData' => $userData
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			$result = $this->validate($postData,'User.add',[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$userModel = new User();
			// $postData['password'] = md5($postData['password'].config('password_salt'));
			if ($userModel->allowField(true)->save($postData)) {
				$this->success('添加成功',url('/admin/user/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$roles = Role::select();
		return $this->fetch('',[
			'roles' => $roles
		]);
	}
}