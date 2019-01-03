<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;

Route::get('/','home/index/index');
Route::get('/backstage','admin/index/index');

Route::group('home',function(){
	//分类列表
	Route::get('/category/index','home/category/index');

	//展示注册模板
	Route::any('/login/register','home/login/register');

	//展示登陆模板
	Route::any('/login/login','home/login/login');
	Route::get('/login/logout','home/login/logout');

	//展示商品详情页
	Route::get('/goods/detail','home/goods/detail');

	//清空历史访问
	Route::get('/goods/cleanHistory','home/goods/cleanHistory');

	//发送手机验证码
	Route::post('/login/sendSms','home/login/sendSms');
});

Route::group('admin',function(){

	//展示后台主页模板
	Route::get('/index/index','admin/index/index');
	Route::get('/index/top','admin/index/top');
	Route::get('/index/left','admin/index/left');
	Route::get('/index/main','admin/index/main');

	//展示后台登陆模板
	Route::any('/login/login','admin/login/login');
	Route::any('/login/logout','admin/login/logout');

	//展示用户管理模板
	Route::any('/user/add','admin/user/add');	  //增
	Route::get('/user/del','admin/user/del');	  //删
	Route::any('/user/upd','admin/user/upd');	  //改
	Route::any('/user/index','admin/user/index'); //查

	//展示权限管理模板
	Route::any('/auth/add','admin/auth/add');	  //增
	Route::get('/auth/index','admin/auth/index'); //查
	Route::any('/auth/upd','admin/auth/upd');	  //改
	Route::get('/auth/ajaxDel','admin/auth/ajaxDel');	  //删

	//展示角色管理模板
	Route::any('/role/add','admin/role/add');
	Route::get('/role/index','admin/role/index');
	Route::get('/role/del','admin/role/del');
	Route::any('/role/upd','admin/role/upd');

	//展示商品类型模板
	Route::any('/type/add','admin/type/add');	  //增
	Route::get('/type/del','admin/type/del');	  //删
	Route::any('/type/upd','admin/type/upd');	  //改
	Route::get('/type/index','admin/type/index'); //查

	//展示商品属性模板
	Route::any('/attribute/add','admin/attribute/add');	  //增
	Route::get('/attribute/del','admin/attribute/del');	  //删
	Route::any('/attribute/upd','admin/attribute/upd');	  //改
	Route::get('/attribute/index','admin/attribute/index'); //查

	//展示商品分类模板
	Route::any('/category/add','admin/category/add');	  //增
	Route::get('/category/del','admin/category/del');	  //删
	Route::any('/category/upd','admin/category/upd');	  //改
	Route::get('/category/index','admin/category/index'); //查

	//展示商品管理模板
	Route::any('/goods/add','admin/goods/add');	  //增
	Route::get('/goods/del','admin/goods/del');	  //删
	Route::any('/goods/upd','admin/goods/upd');	  //改
	Route::get('/goods/index','admin/goods/index'); //查

	//商品属性
	Route::get('/Goods/getTypeAttr','admin/Goods/getTypeAttr');
});
