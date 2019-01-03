<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
error_reporting(0);

//发送短信的方法
function SendSms($to,$datas,$tempId = '1'){

	include_once("../extend/sendSms/CCPRestSmsSDK.php");

	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
	$accountSid= '8a216da867e881cb0167fd7ae9940d21';

	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
	$accountToken= 'a763aa52ad8f4c9c8674210754263748';

	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
	$appId='8a216da867e881cb0167fd7ae9f60d28';

	//请求地址
	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
	//生产环境（用户应用上线使用）：app.cloopen.com
	$serverIP='app.cloopen.com';


	//请求端口，生产环境和沙盒环境一致
	$serverPort='8883';

	//REST版本号，在官网文档REST介绍中获得。
	$softVersion='2013-12-26';

     // 初始化REST SDK
     //global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     return $result;
}