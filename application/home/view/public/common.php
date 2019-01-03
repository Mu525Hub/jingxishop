<?php

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

//导航栏
public function getNavCat(){
	$where = [
		'is_show' => 1,
		'pid' => 0
	];
	return $this->where($where)->select();
}

//递归找祖先
public function getFamilyCats($data,$cat_id){
	static $result = [];
	foreach($data as $v){
		if ($v['cat_id'] == $cat_id) {
			$result[] = $v;
			$this->getFamilyCats($data,$v['pid']);
		}
	}
	return array_reverse($result);
}

//递归找子孙
public function getSonsCats($data,$cat_id){
	static $result = [];
	foreach($data as $v){
		if ($v['pid'] == $cat_id) {
			$result[] = $v['cat_id'];
			$this->getSonsCats($data,$v['cat_id']);
		}
	}

	return $result;
}