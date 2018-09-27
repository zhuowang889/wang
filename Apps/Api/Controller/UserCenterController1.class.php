<?php
namespace Api\Controller;
use Think\Controller;
/*
 * @desc 用户中心API
 */
class UserCenterController extends Controller {
	/**
	 * sso登陆接口
	 */
    public function index()
    {
    	//如果用户已登录, 则直接读取cookie,发送用户数据,这种方法是否有问题有待验证
    	if(cookie('ssouser')){
    		$k = cookie('ssouser');
    		echo json_encode(S($k));
			die;
    	}
        //模拟数据，待删除
        $_POST = ['user_name'=>'admin', 'password'=>'123456', 'referer'=>''];
    	$referer = I('post.referer', '', 'trim');
    	if($uid = D('admin')->checkPassword()){
    	    $status = D('admin')->getUserinfo($uid, 'status');
    	    if($status == 2){
    	        res(0, '用户不允许登录');
    	    }
    	    if($key = D('admin')->setUserSSO($uid)){
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
            $uid = D('admin')->getUidFromSSOkey($SSOkey);
        }else{
            $uid = I('post.id', 0, 'intval');
        }
        
        if($userInfo = D('admin')->getUserinfo($uid)){
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
        if($uInfo = D('admin')->checkLogin($ssoKey, $uid)){
            res([1,$uInfo], '已经登陆');
        }else{
            res(0, '未登录或过期');
        }
    }
    /**
     * user 退出
     */
    public function loginOut() {
        //模拟数据，待删除
        $_POST = ['u'=>'a7d0e7a977'];
        $SSOkey = I('post.u', '', 'trim');
        
        if(S($SSOkey, NULL, ['data_cache_prifix'=>D('admin')->get('SSOpre')])){
            res(1, '退出成功');
        }else{
            res(0, '退出失败');
        }
    }

}