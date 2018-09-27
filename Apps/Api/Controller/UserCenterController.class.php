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
    	header("Access-Control-Allow-Credentials: true");
    	//header('Access-Control-Allow-Origin:http://10.8.66.111:8009'); 
    	header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
    	 //if(cookie('ssouser')){
    	 	$user_name = I('post.user_name');
    	 	$password = I('post.password');
    	 	if(empty($user_name) || empty($password)){
	    	 	$arr = S(cookie('ssouser'));
	    	 	//单点退出只是加了一个标识，并没有消除cookie
	    	 	$logout = I('post.login')?I('post.login'):'';
	    	 	if($logout=='logout'){//退出
	    	 		$arr['login'] = 'logout';
	    	 		$k = cookie('ssouser');
	    	 		S($k, $arr, ['expire'=>$this->adminModel->loginExpire, 'data_cache_prifix'=>$this->adminModel->SSOpre]);
	    	 		$result = ['code'=>-1,'msg'=>'退出成功!'];
	    	 	}elseif($logout=='loging'){//登录
	    	 		$arr['login'] = 'loging';
	    	 		$k = cookie('ssouser');
	    	 		S($k, $arr, ['expire'=>$this->adminModel->loginExpire, 'data_cache_prifix'=>$this->adminModel->SSOpre]);
	    	 		$result = ['code'=>1,'msg'=>'登录成功!'];
	    	 	}
	    	 	
	    	 	if($logout){
	    	 		echo '//这个值要保留,删除注释';
	    	 		echo json_encode(array_merge($result,$arr),JSON_UNESCAPED_UNICODE);
	    	 	}else{
	    	 		echo '//这里只是方便测试拿取cookie，正式上线需要删除这个区间，因为正式上线会传参数login';
	    	 		echo json_encode($arr,JSON_UNESCAPED_UNICODE);
	    	 	}
	    	  die;
    	 }else{
    	 	//===============
    	 	//模拟数据，待删除
    	 	// $_POST = ['user_name'=>'zcj', 'password'=>'123456', 'referer'=>'http://www.sohu.com'];
    	 	$referer = I('post.referer', '', 'trim');
    	 	if($uid = $this->adminModel->checkPassword()){
    	 		$status = $this->adminModel->getUserinfo($uid, 'status');
    	 		if($status == 2){
    	 			res(0, '用户不允许登陆');
    	 		}
    	 		if($key = $this->adminModel->setUserSSO($uid)){
    	 			$referer && $referer .= (strpos($referer, '?')===false ? '?' : '&').'u='.$key;
    	 			res(1, '登陆成功', $referer,$key);
    	 		}
    	 		res(0, '登陆失败');
    	 	}else{
    	 		res(0, '用户名或密码错误');
    	 	}
    	 	//==================
    	 }
    	//}  
        
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
    /**
     * user 退出
     */
    public function loginOut() {
        //模拟数据，待删除
        $_POST = ['u'=>'a7d0e7a977'];
        $SSOkey = I('post.u', '', 'trim');
        
        if(S($SSOkey, NULL, ['data_cache_prifix'=>$this->adminModel->get('SSOpre')])){
            res(1, '退出成功');
        }else{
            res(0, '退出失败');
        }
    }

}