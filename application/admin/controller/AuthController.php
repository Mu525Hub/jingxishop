<?php

namespace app\admin\controller;
use app\admin\model\Auth;
use think\Db;

class AuthController extends CommonController{
	public function ajaxDel(){
		if (request()->isAjax()) {
			$auth_id = input('auth_id');
			$sons = Auth::where('pid','=',$auth_id)->find();
			if ($sons) {
				$response = ['code'=>'-1','message'=>'含有子权限，无法删除'];
				echo json_encode($response);die;
			}
			if (Auth::destroy($auth_id)) {
				$response = ['code'=>'200','message'=>'删除成功'];
				echo json_encode($response);die;
			} else {
				$response = ['code'=>'-2','message'=>'删除失败'];
				echo json_encode($response);die;
			}
		}
	}

	public function upd(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			if ($postData['pid'] != 0) {
				$result = $this->validate($postData,"Auth.upd",[],true);
			} else {
				$result = $this->validate($postData,"Auth.checkAuthName",[],true);
			}
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			// 根据pid查询对应的auth_name
			$pid_auth_name = Db::name('auth')->where('auth_id',$postData['pid'])->value('auth_name');

			if ($pid_auth_name == $postData['auth_name']) {
				$this->error('权限名称与父级权限一致');
			}
			//4、写入数据库
			$AuthModel = new Auth();
			if ($AuthModel->update($postData)) {
				$this->success('修改成功',url('/admin/Auth/index'));
			} else {
				$this->error('修改失败');
			}
		}
		$auth_id = input('auth_id');
		$authModel = new Auth();
		$authData = $authModel->find($auth_id);
		$auths = $authModel->select();
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'authData' => $authData,
			'authsTree' => $authsTree
		]);
	}

	public function index(){
		$authModel = new Auth();
		$auths = $authModel
			->field('t1.*,t2.auth_name p_name')
			->alias('t1')
			->join('sh_auth t2','t1.pid = t2.auth_id','left')
			->select();
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'auths' => $authsTree
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			if ($postData['pid'] != 0) {
				$result = $this->validate($postData,"Auth.add",[],true);
			} else {
				$result = $this->validate($postData,'Auth.checkAuthName',[],true);
			}
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$authModel = new Auth();
			if ($authModel->save($postData)) {
				$this->success('添加成功',url('/admin/auth/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$authModel = new Auth();
		$auths = $authModel->select();
		$authsTree = $authModel->getSonsTree($auths);
		return $this->fetch('',[
			'authsTree' => $authsTree
		]);
	}
}