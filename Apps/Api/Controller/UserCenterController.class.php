<?php
namespace Api\Controller;
use Think\Controller;
use Api\Model\AdminModel;
/*
 * @desc 用户中心API
 */
class UserCenterController extends Controller {
    private $adminModel;
    protected function _initialize(){
        $this->adminModel = new AdminModel();
    }
    
	/**
	 * sso登陆接口
	 */
    public function index()
    {
        //模拟数据，待删除
        $_POST = ['user_name'=>'admin', 'password'=>'123456', 'referer'=>''];
    	$referer = I('post.referer', '', 'trim');
    	if($uid = $this->adminModel->checkPassword()){
    	    $status = $this->adminModel->getUserinfo($uid, 'status');
    	    if($status == 2){
    	        res(0, '用户不允许登陆');
    	    }
    	    if($key = $this->adminModel->setUserSSO($uid)){
    	        $referer && $referer .= (strpos($referer, '?')===false ? '?' : '&').'u='.$key;
    	        res(1, '登陆成功', $referer);
    	    }
    	    res(0, '登陆失败');
    	}else{
    	    res(0, '用户名或密码错误');
    	}
    }
    
    /**
     * user 获取用户信息
     */
    public function userinfo() {
        //模拟数据，待删除
        $_POST = ['id'=>1, 'u'=>'a7d0e7a977'];
        if($SSOkey = I('post.u', '', 'trim')){
            $uid = $this->adminModel->getUidFromSSOkey($SSOkey);
        }else{
            $uid = I('post.id', 0, 'intval');
        }
        
        if($userInfo = $this->adminModel->getUserinfo($uid)){
           res([1, $userInfo]); 
        }else{
           res(0, '不存在此用户或未登录'); 
        }
    }
    
    /**
     * user 检查是否登陆
     */
    public function checkLogin() {
        //模拟数据，待删除
        $_POST = ['id'=>1];
        $ssoKey = I('post.u', '', 'trim');
        $uid = I('post.id', 0, 'intval');
        if($uInfo = $this->adminModel->checkLogin($ssoKey, $uid)){
            res([1,$uInfo], '已经登陆');
        }else{
            res(0, '未登录或过期');
        }
    }

}