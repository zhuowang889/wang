<?php
namespace Api\Controller;
use Think\Controller;

class IndexController extends Controller {
	//接口请求广告
    public function index()
    {
    	$zones = 7;
    	$prefix = 'revive-0-';
    	$loc = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	$referer = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	
    	$url ='http://101.132.106.202/www/delivery/asyncspc.php?zones='.$zones.'&prefix='.$prefix.'&loc='.$loc.'&referer='.$referer;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$output = curl_exec($ch);
    	var_dump($output);die;
    	curl_close($ch);
    	return $output;
    }
}