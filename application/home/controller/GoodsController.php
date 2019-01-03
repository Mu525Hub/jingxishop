<?php

namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\Goods;
use app\home\model\Category;

class GoodsController extends Controller{
	public function detail(){
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

		//商品信息
		$goodsModel = new Goods();
		$goods_id = input('goods_id');
		$goodsData = $goodsModel->where('goods_id',$goods_id)->find();

		//面包屑导航
		$familyCats = $catModel->getFamilyCats($oldCats,$goodsData['cat_id']);

		//图片画廊展示
		$goodsData['goods_img'] = json_decode($goodsData['goods_img']);
		$goodsData['goods_middle'] = json_decode($goodsData['goods_middle']);
		$goodsData['goods_thumb'] = json_decode($goodsData['goods_thumb']);

		// dump($goodsData['goods_img']);
		// dump($goodsData['goods_middle']);
		// dump($goodsData['goods_thumb']);die;

		//商品唯一属性值
		$only_attr = Db::name('goods_attr')
			->alias('t1')
			->field('t1.*,t2.attr_name')
			->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
			->where('t1.goods_id',$goods_id)
			->where('t2.attr_type = 0')
			->select();

		//商品单选属性值
		$single_attr = Db::name('goods_attr')
			->alias('t1')
			->field('t1.*,t2.attr_name')
			->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
			->where('t1.goods_id',$goods_id)
			->where('t2.attr_type = 1')
			->select();
		//因为一个单选属性，对应的值可能有多个，所以可以通过属性attr_id惊醒分组
		$singleAttr = [];
		foreach($single_attr as $v){
			$singleAttr[ $v['attr_id'] ][] = $v;
		}
		// halt($only_attr);
		// halt($single_attr);
		// halt($singleAttr);

		//最近浏览商品
		$history = $goodsModel->addGoodsHistory($goods_id);

		return $this->fetch('',[
			'goodsData' => $goodsData,
			'familyCats' => $familyCats,
			'navCat' => $navCat,
			'cats' => $cats,
			'children' => $children,
			'only_attr' => $only_attr,
			'singleAttr' => $singleAttr,
			'history' => $history
		]);
	}

	public function cleanHistory(){
		cookie('history',null);
	}
}