<?php

/**
 * API 返回，0失败，1成功
 * @param number $code
 * @param string $msg
 * @param string $referer
 */
function res($code = 0, $msg = '失败', $referer = ''){
    if($referer){
        header("Location:{$referer}");
    }
    $res = array('code'=>$code, 'msg'=>$msg);
    echo json_encode($res,JSON_UNESCAPED_UNICODE);
    exit;
}
