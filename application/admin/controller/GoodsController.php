<?php

namespace app\admin\controller;
use app\admin\model\Goods;
use app\admin\model\Type;
use app\admin\model\Category;
use app\admin\model\Attribute;
class GoodsController extends CommonController{
	public function index(){
		$goods = Goods::alias('t1')
			->field('t1.*,t2.cat_name')
			->join('sh_category t2','t1.cat_id = t2.cat_id','left')
			->select();
		return $this->fetch('',[
			'goods' => $goods
		]);
	}

	public function add(){
		//1、判断是否为post请求
		if (request()->isPost()) {
			//2、接收post传递的数据
			$postData = input('post.');
			// dump($postData);die;
			//3、验证
			$result = $this->validate($postData,"Goods.add",[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}

			//处理图片
			$goods_img = $this->uploadImg();
			if ($goods_img) {
				$postData['goods_img'] = json_encode($goods_img);
				$thumb_arr = $this->getThumbImg($goods_img);
				$postData['goods_middle'] = json_encode($thumb_arr['goods_middle']);
				$postData['goods_thumb'] = json_encode($thumb_arr['goods_thumb']);
			}
			//4、写入数据库
			$goodsModel = new Goods();
			if ($goodsModel->allowField(true)->save($postData)) {
				$this->success('添加成功',url('/admin/goods/index'));
			} else {
				$this->error('添加失败');
			}
		}

		$catModel = new Category();
		$cats = $catModel->select();

		$types = Type::select();
		$catsTree = $catModel->getSonsTree($cats);
		return $this->fetch('',[
			'cats' => $catsTree,
			'types' => $types
		]);
	}

	//上传图片
	public function uploadImg(){
		$result = [];
		$files = request()->file('img');
		//定义移动的目录
		$uploadDir = "./uploads/";
		//定义上传的要求
		$validate = [
			'size' => 1024*1024*4,
			'ext' => 'jpg,jpeg,png,gif'
		];
		//移动到指定的目录下
		foreach($files as $file){
			$info = $file->validate($validate)->move($uploadDir);
			if ($info) {
				$result[] = str_replace('\\','/',$info->getSaveName());
			}
		}
		return $result;
	}

	//缩略图
	public function getThumbImg($goods_img){
		$goods_middle = [];
		$goods_thumb = [];

		//生成350*350的缩略图
		foreach($goods_img as $path){
			$images = \think\Image::open('./uploads/'.$path);
			$arr = explode('/',$path);// [20181225,asd6546549815]
			$middle_path = $arr[0].'/middle_'.$arr[1];
			$images->thumb(350,350,2)->save('./uploads/'.$middle_path);
			$goods_middle[] = $middle_path;
		}
		//生成50*50的缩略图
		foreach($goods_img as $path){
			$images = \think\Image::open('./uploads/'.$path);
			$arr = explode('/', $path);
			$thumb_path = $arr[0].'/thumb_'.$arr[1];
			$images->thumb(50,50,2)->save('./uploads/'.$thumb_path);
			$goods_thumb[] = $thumb_path;
		}

		return ['goods_middle'=>$goods_middle,'goods_thumb'=>$goods_thumb];
	}

	//获取类型属性
	public function getTypeAttr(){
		if (request()->isAjax()) {
			$type_id = input('type_id');
			$attrs = Attribute::where('type_id',$type_id)->select();
			echo json_encode($attrs);
		}
	}
}