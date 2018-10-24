<?php
namespace Api\Controller;
use Think\Controller;

class DspController extends Controller {
	//登录生成cookie
    public function index()
    {
        $cookieFile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Conf'.DIRECTORY_SEPARATOR.'cookie_revive_api';
        $domain = '127.0.0.1';
        $revivLoginUrl = 'http://'.$domain.'/revive/www/admin/index.php';
        $sessionid = '1w2r3f4';
        $curl_cookie = "sessionID={$sessionid}; test=123";
        /*登录时候清空coookiefile*/
        file_put_contents($cookieFile, '');
        $info = ['c'=>$curl_cookie];
        $userAuth = ['oa_cookiecheck'=>$sessionid, 'username'=>'admin', 'password'=>'123456', 'login'=>'Login'];
        $info += ['cf'=>$cookieFile, 'd'=>$userAuth];
        $xx = http_call($revivLoginUrl, true, $info);
        $testfile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Conf'.DIRECTORY_SEPARATOR.'test.html';
        file_put_contents($testfile, $xx);
    }
  
    public function godash(){
        $cookieFile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Conf'.DIRECTORY_SEPARATOR.'cookie_revive_api';
        $domain = '127.0.0.1';
        $url = 'http://'.$domain.'/revive/www/admin/dashboard.php';
        $info = ['cf'=>$cookieFile];
        $xx = http_call($url, false, $info);
        $testfile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Conf'.DIRECTORY_SEPARATOR.'test.html';
        file_put_contents($testfile, $xx);
    }

}