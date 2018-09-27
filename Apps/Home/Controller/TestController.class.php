<?php
namespace Home\Controller;
use Think\Controller;

class TestController extends Controller {
    
    public function index()
    {
       //$x =  new \Api\Controller\UserCenterController();
       //$mypost = ['user_name'=>'admin', 'password'=>'123456', 'referer'=>''];
       //dump($x->index($mypost));
       //$url = 'http://10.8.102.37:8009/Sso/Login/ssoUrl';
       $url = 'http://10.8.102.37:8009/Api/UserCenter/index';
       //$url = 'http://10.8.66.111:8009/index/Api/UserCenter/index';
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['user_name'=>'admin','password'=>'123456']);
     // curl_setopt($ch, CURLOPT_POSTFIELDS, ['name'=>'admin','pwd'=>'123456']);
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_HEADER, 0);
       $output = curl_exec($ch);
       curl_close($ch);
       echo $output;
    }
}