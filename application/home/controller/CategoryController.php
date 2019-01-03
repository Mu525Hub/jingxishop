<?php

namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;

class CategoryController extends Controller{
	public function index(){
		//导航栏
		$catModel = new Category();
		$navCat = $catModel->getNavCat();

		//左侧导航栏
		$oldCats = $catModel->select()->toArray();
		$cats = [];
		foreach($oldCats as $v){
			$cats[ $v['cat_id'] ] = $v;
		}
		$children = [];
		foreach($oldCats as $v){
			$children[ $v['pid'] ][] = $v['cat_id'];
		}

		//面包屑
		$cat_id = input('cat_id');
		//递归找祖先
		$familyCats = $catModel->getFamilyCats($oldCats,$cat_id);

		//递归找子孙
		$sonsCats = $catModel->getSonsCats($oldCats,$cat_id);
		$sonsCats[] = intval($cat_id);
		$where = [
			'is_sale' => 1,
			'is_delete' => 0,
			'cat_id' => ['in',$sonsCats]
		];
		$goods = Goods::where($where)->select();

		$goodsModel = new Goods();
		$newGoods = $goodsModel->getTypeGoods('is_new',3);
		$hotGoods = $goodsModel->getTypeGoods('is_hot',3);

		return $this->fetch('',[
			'navCat' => $navCat,
			'cats' => $cats,
			'children' => $children,
			'familyCats' => $familyCats,
			'goods' => $goods,
			'newGoods' => $newGoods,
			'hotGoods' => $hotGoods
		]);
	}
}