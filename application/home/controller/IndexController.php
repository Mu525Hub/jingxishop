<?php

namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;

class IndexController extends Controller{
	public function index(){
		//导航栏
		$catModel = new Category();
		$navCat = $catModel->getNavCat();

		//左侧导航栏
		$oldCats = $catModel->select();
		$cats = [];
		foreach($oldCats as $v){
			$cats[ $v['cat_id'] ] = $v;
		}
		$children = [];
		foreach($oldCats as $v){
			$children[ $v['pid'] ][] = $v['cat_id'];
		}

		//推荐栏
		$goodsModel = new Goods();
		$hotGoods = $goodsModel->getTypeGoods('is_hot',5);
		$bestGoods = $goodsModel->getTypeGoods('is_best',5);
		$crazyGoods = $goodsModel->getTypeGoods('is_crazy',5);
		$newGoods = $goodsModel->getTypeGoods('is_new',5);

		return $this->fetch('',[
			'navCat' => $navCat,
			'cats' => $cats,
			'children' => $children,
			'hotGoods' => $hotGoods,
			'bestGoods' => $bestGoods,
			'crazyGoods' => $crazyGoods,
			'newGoods' => $newGoods
		]);
	}
}