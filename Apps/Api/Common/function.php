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
    echo json_encode($res,JSON_UNESCAPED_UNICODE);
   exit;
}
