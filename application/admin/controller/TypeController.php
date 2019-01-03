<?php

namespace app\admin\controller;
use app\admin\model\Type;

class TypeController extends CommonController{
	public function upd(){
		//1、判断是否为post请求
			if (request()->isPost()) {
				//2、接收post传递的数据
				$postData = input('post.');
				//3、验证
				$result = $this->validate($postData,"Type.upd",[],true);
				if ($result !== true) {
					$this->error(implode(',',$result));
				}
				//4、写入数据库
				$typeModel = new Type();
				if ($typeModel->update($postData)) {
					$this->success('编辑成功',url('/admin/type/index'));
				} else {
					$this->error('编辑失败');
				}
			}

		$type_id = input('type_id');
		$typeData = Type::find($type_id);
		return $this->fetch('',[
			'typeData' => $typeData
		]);
	}

	public function del(){
		$type_id = input('type_id');
		if (Type::destroy($type_id)) {
			$this->redirect('/admin/type/index');
		}
	}

	public function index(){
		$types = Type::select();
		return $this->fetch('',[
			'types' => $types
		]);
	}

	public function add(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'Type.add',[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			$typeModel = new Type();
			if($typeModel->save($postData)) {
				$this->success('添加成功',url('/admin/type/index'));
			} else {
				$this->error('添加失败');
			}
		}
		return $this->fetch();
	}
}