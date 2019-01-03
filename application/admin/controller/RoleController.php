<?php

namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;

class RoleController extends CommonController{
	public function del(){
		$role_id = input('role_id');
		$result = Role::destroy($role_id);
		if ($result) {
			$this->redirect('/admin/role/index');
		}else{
			$this->error('删除失败');
		}
	}

	public function upd(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			$result = $this->validate($postData,"Role.upd",[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$roleModel = new Role();
			if ($roleModel->update($postData)) {
				$this->success('修改成功',url('/admin/role/index'));
			} else {
				$this->error('修改失败');
			}
		}

		$role_id = input('role_id');
		$roleData = Role::find($role_id);
		//取出所有的权限数据
		$auths = Auth::select()->toArray();
		$auth = [];
		foreach($auths as $v){
			$auth[ $v['auth_id'] ] = $v;
		}
		$children = [];
		foreach($auths as $v){
			$children[ $v['pid'] ][] = $v['auth_id'];
		}
		return $this->fetch('',[
			'roleData' => $roleData,
			'auth' => $auth,
			'children' => $children
		]);
	}

	public function index(){
		$sql = "select t1.*,GROUP_CONCAT(t2.auth_name SEPARATOR '|') as allAuth from sh_role as t1 LEFT JOIN sh_auth as t2 on FIND_IN_SET(t2.auth_id,t1.auth_ids_list) GROUP BY t1.role_id";
		$roles = Db::query($sql);
		return $this->fetch('',[
			'roles' => $roles
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			$result = $this->validate($postData,"Role.add",[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$roleModel = new Role();
			if ($roleModel->save($postData)) {
				$this->success('添加成功',url('/admin/role/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$auths = Auth::select()->toArray();
		//技巧1：以每个元素的主键值作为其对应的下标
		$auth = [];
		foreach($auths as $v){
			$auth[ $v['auth_id'] ] = $v;
		}
		// dump($auth);die;
		//技巧2：把每个元素通过pid进行分组，即把相同pid的元素划分为同一组
		$children = [];
		foreach($auths as $v){
			$children[ $v['pid'] ][] = $v['auth_id'];
		}
		// dump($children);die;
		return $this->fetch('',[
			'auth' => $auth,
			'children' => $children
		]);
	}
}