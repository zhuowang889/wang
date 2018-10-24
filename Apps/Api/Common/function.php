<?php

/**
 * API 返回，0失败，1成功
 * @param int|array $code_data
 * @param string $msg
 * @param string $referer
 */
function res($code_data, $msg = '', $referer = '',$key = ''){
    if(is_numeric($code_data)){
        $code = $code_data;
    }else{
        list($code, $data) = $code_data;
    }
    if($referer){
        header("Location:{$referer}");
    }
    if($key){
    	$userInfo = S($key);	
    }
    $res = array('code'=>$code);
    isset($data) && $res['data'] = $data;
    $res['msg'] = $msg;
    $res['id'] = $userInfo['id'];
    $res['username'] = $userInfo['user_name'];
    $res['login'] = 'loging';
    echo json_encode($res,JSON_UNESCAPED_UNICODE);
   exit;
}

/**
 * curl 请求
 * @param string $url
 * @param boolean $post
 * @param array $info h:header,t:time,d:data,c:cookie,cf:cookiefile
 * @return mixed
 */
function http_call($url, $post = false, array $info = ['h'=>'','t'=>5, 'd'=>'']){
    $info = array_merge(['h'=>'','t'=>5, 'd'=>''], $info);
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    if($info['h']){
        curl_setopt($ch, CURLOPT_HEADER, $info['h']);
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if($post){
        curl_setopt($ch, CURLOPT_POST, true);
        $info['d'] && curl_setopt($ch, CURLOPT_POSTFIELDS, $info['d']);
    }
    if($info['c']){
        curl_setopt($ch, CURLOPT_COOKIE, $info['c']);
    }
    if($info['cf']){
        curl_setopt($ch, CURLOPT_COOKIEFILE, $info['cf']);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $info['cf']);
    }

    curl_setopt($ch, CURLOPT_TIMEOUT, $info['t']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    $res = curl_exec($ch);
    return $res;
}