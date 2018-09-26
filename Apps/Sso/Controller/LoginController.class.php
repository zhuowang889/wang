<?php
namespace Sso\Controller;
use Think\Controller;

class LoginController extends Controller {
    
    /**
     * 登录页面显示
     * @author zcj
     */
    public function login()
    {
        $this->display();
    }
    
    /**
     * 登录操作
     * @author zcj
     */
    public function doLogin()
    {
        $name = I('post.name');
        $pwd = I('post.pwd');
        $captcha = I('post.captcha');
        if(!$name){
            $this->ajaxReturn(array('status'=>1,'msg'=>'请输入用户名！'));
        }
        if(!$pwd){
            $this->ajaxReturn(array('status'=>1,'msg'=>'请输入密码！'));
        }
        if(!$captcha){
            $this->ajaxReturn(array('status'=>1,'msg'=>'请输入验证码！'));
        }
        $verify = new \Think\Verify();
        if(!$verify->check($captcha, '')){
            $this->ajaxReturn(array('status'=>1,'msg'=>'验证码不正确，请重新输入！'));
        }
        $admin_user_model = D("Admin");
        $user_info = $admin_user_model->findUser($name, $pwd);
        
        if(!$user_info){
            $this->ajaxReturn(array('status'=>1,'msg'=>'用户名或密码不正确，请重新输入！'));
        }
        
        $admin_user_model->updateLoginTime($user_info['id']);
        
        session('user_info', $user_info);
        $this->ajaxReturn(array('status'=>0,'msg'=>'登录成功！'));
        
    }
    
    /**
     * 生成验证码
     * @author zcj
     */
    public function verify()
    {
        $verify = new \Think\Verify();
        $verify->length   = 4;
        $verify->codeSet = '0123456789';
        $verify->fontttf = '5.ttf';
        $verify->imageW = 130;
        $verify->imageH = 37;
        $verify->fontSize = 16;
        $verify->bg = array(220,220,220);
        $verify->entry();
    }
    
    /**
     * 退出登录
     * @author zcj
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('Login/login');
    }
    /**
     * @desc post请求接口
     * @param string $url
     * @param string $param
     * @return boolean|mixed
     */
    public function curlPost($url,$param){
    	//$url = 'http://10.8.102.37:8009/Sso/Login/ssoUrl';
    	//$url = 'http://10.8.102.37:8009/Api/UserCenter/index';
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    	//curl_setopt($ch, CURLOPT_POSTFIELDS, ['name'=>'admin','pwd'=>'123456']);
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$output = curl_exec($ch);
    	curl_close($ch);
    	dump($output);
    	/* $ch = curl_init ();
    	curl_setopt ( $ch, CURLOPT_URL, $url );
    	curl_setopt ( $ch, CURLOPT_POST, 1 );
    	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $param );
    	$return = curl_exec ( $ch );
    	curl_close ( $ch );
    	echo $return;
    	return $return; */
    }
}