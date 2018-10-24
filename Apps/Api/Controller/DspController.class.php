<?php
namespace Api\Controller;
use Think\Controller;

class DspController extends Controller {
	//接口请求图片广告
    public function index()
    {
    	
    }
  
    //post请求
	function httpPost($url='',$header=''){
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    function imgGet($param=''){
    	$header = array("Accept: image/webp,image/apng,image/*,*/*;q=0.8");
    	$ch=curl_init($param);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    	curl_setopt($ch,CURLOPT_POST,0);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	$res = curl_exec($ch);
    	curl_close($ch);
    }
}