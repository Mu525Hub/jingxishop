<?php

namespace app\home\controller;
use think\Controller;
use app\home\model\Member;

class LoginController extends Controller{
	public function sendSms(){
		//1、判断是否为ajax请求
		if (request()->isAjax()) {
			//2、接收手机号
			$phone = input('phone');
			//3、判断手机号是否被注册过
			$memberInfo = Member::where('phone',$phone)->find();
			if ($memberInfo) {
				$response = ['code'=>-1,'message'=>'此号码已被注册'];
				echo json_encode($response);die;
			}
			$code = mt_rand(1000,9999);

			cookie('sms',md5($code.config('sms_salt')),300);
			$result = sendSms($phone,[$code,5]);
			if ($result->statusCode == '000000') {
				$response = ['code'=>200,'message'=>'发送成功，请查收'];
				echo json_encode($response);die;
			} else {
				$response = ['code'=>-2,'message'=>'发送失败，原因：'.$result->statusMsg];
				echo json_encode($response);die;
			}
		}
	}

	public function logout(){
		session('member_id',null);
		session('username',null);
		$this->redirect('/home/login/login');
	}

	public function login(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'Member.login',[],true);
			if ($result!==true) {
				$this->error(implode(',',$result));
			}
			$memberModel = new Member();
			$status = $memberModel->checkUser($postData['username'],$postData['password']);
			if ($status) {
				$this->redirect('/');
			}else{
				$this->error("账号或密码错误");
			}
		}
		return $this->fetch();
	}

	public function register(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'Member.register',[],true);
			if ($result !== true) {
				$this->error(implode(',',$result));
			}
			$memberModel = new Member();
			if ($memberModel->allowField(true)->save($postData)) {
				$this->success('注册成功',url('/home/login/login'));
			} else {
				$this->error('注册失败');
			}
		}
		return $this->fetch();
	}
}