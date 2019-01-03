<?php

namespace app\admin\controller;
use app\admin\model\Type;
use app\admin\model\Attribute;

class AttributeController extends CommonController{
	public function upd(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			
			//3、验证
			if ($postData['attr_input_type'] == 1) {
				$result = $this->validate($postData,"Attribute.upd",[],true);
			} else {
				$result = $this->validate($postData,"Attribute.checkAttrName",[],true);
			}
			
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$attributeModel = new Attribute();

			if ($attributeModel->allowField(true)->update($postData)) {
				$this->success('编辑成功',url('/admin/attribute/index'));
			} else {
				$this->error('编辑失败');
			}
		}

		$attr_id = input('attr_id');
		$attr = Attribute::find($attr_id);
		$types = Type::select();
		return $this->fetch('',[
			'attr' => $attr, 
			'types' => $types
		]);
	}

	public function del(){
		$attr_id = input('attr_id');
		$result = Attribute::destroy($attr_id);
		if ($result) {
			$this->redirect('/admin/attribute/index');
		} else {
			$this->error('删除失败');
		}
	}

	public function index(){
		$attrs = Attribute::alias('t1')
		->field('t1.*,t2.type_name')
		->join('sh_type t2','t1.type_id = t2.type_id','left')
		->select();
		return $this->fetch('',[
			'attrs' => $attrs
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			if ($postData['attr_input_type'] == 1) {
				$result = $this->validate($postData,"Attribute.add",[],true);
			} else {
				$result = $this->validate($postData,"Attribute.checkAttrName",[],true);
			}
			
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$attributeModel = new Attribute();
			if ($attributeModel->allowField(true)->save($postData)) {
				$this->success('添加成功',url('/admin/attribute/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$types = Type::select();
		return $this->fetch('',[
			'types' => $types
		]);
	}
}