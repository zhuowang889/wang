<?php
namespace Api\Controller;
use Think\Controller;
use Think\Think;
use Api\Model;
use Api\Model\AdminModel;
/*
 * @desc 用户中心API
 */
class UserCenterController extends Controller {
    
	/**
	 * sso登陆接口
	 */
    public function index()
    {
        //模拟数据，待删除
        $_POST = ['user_name'=>'admin', 'password'=>'123456'];
    	$refer = I('refer', '', 'trim');
    	$adminModel = new AdminModel();
    	if($uid = $adminModel->checkLogin()){
    	    $userInfo = $adminModel->getUserinfo($uid);
    	    if($userInfo['status']==2){
    	        res(0, '用户不允许登陆');
    	    }
    	    if($adminModel->setUserSSO($uid)){
    	        res(1, '登陆成功', $refer);
    	    }
    	}else{
    	    res(0, '用户名或密码错误');
    	}
    	res();
    }

   
}