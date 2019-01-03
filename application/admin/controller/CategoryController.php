<?php

namespace app\admin\controller;
use app\admin\model\Category;

class CategoryController extends CommonController{
	public function upd(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			$result = $this->validate($postData,"Category.upd",[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$categoryModel = new Category();
			if ($categoryModel->update($postData)) {
				$this->success('修改成功',url('/admin/category/index'));
			} else {
				$this->error('修改失败');
			}
		}

		$cat_id = input('cat_id');
		$catModel = new Category();
		$catData = $catModel->find($cat_id);
		$cats = $catModel->select();
		$catsTree = $catModel->getSonsTree($cats);
				// dump($catsTree);die;
		return $this->fetch('',[
			'catData' => $catData,
			'cats' => $catsTree
		]);
	}

	public function del() {
		$cat_id = input('cat_id');
		$sons = Category::where('pid','=',$cat_id)->select()->toArray();
		if ($sons) {
			//有子孙，无法删除
			$this->error('含有子分类，无法删除');
		}
		if (Category::destroy($cat_id)) {
			$this->redirect('/admin/category/index');
		}else{
			$this->error('删除失败');
		}
	}

	public function index(){
		$catModel = new Category();
		$cats = $catModel->select()->toArray();
		$catsTree = $catModel->getSonsTree($cats);
		return $this->fetch('',[
			'cats' => $catsTree
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			//3、验证
			$result = $this->validate($postData,"Category.add",[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			//4、写入数据库
			$categoryModel = new Category();
			if ($categoryModel->save($postData)) {
				$this->success('添加成功',url('/admin/category/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$catModel = new Category();
		$cats = $catModel->select();
		$catsTree = $catModel->getSonsTree($cats);
		return $this->fetch('',[
			'cats' => $catsTree
		]);
	}
}