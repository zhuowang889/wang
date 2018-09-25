<?php
namespace Api\Controller;
use Think\Controller;

class IndexController extends Controller {
    public $adServerUrl = 'http://101.132.106.202/www';
    
	//接口请求图片广告
    public function index()
    {
    	$zones = I('get.zoneId');
    	//正式部署要删除掉具体参数
    	$zones = 7;
    	$prefix = 'revive-0-';
    	$loc = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	$referer = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	
    	$url = $this->adServerUrl.'/delivery/asyncspc.php?zones='.$zones.'&prefix='.$prefix.'&loc='.$loc.'&referer='.$referer;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$output = curl_exec($ch);
    	curl_close($ch);
    	$arr = json_decode($output,true);
    	$html = $arr[$prefix.'0']['html'];
    	$matches = array();
    	$res = array();
    	if(preg_match("/href='([^']+)'.+?src='([^']+)'.+?src='([^']+)'/", $html, $matches)){
    	    $res['clickUrl'] = $matches[1];
    	    $res['imgUrl'] = $matches[2];
    	    $res['callBackParam'] = $matches[3];
    	    $res['width'] = $arr[$prefix.'0']['width'];
    	    $res['height'] = $arr[$prefix.'0']['height'];
    	}
    	//echo '<pre/>';
    	//print_r($res);
    	if($res){
    	   echo json_encode($res);
    	}else{
    	   echo json_encode(array('code'=>0,'msg'=>'广告已停用'));
    	}
    }
    /**
     * DHTML广告
     * @return mixed[]|mixed
     */
    public function dhtmlAd()
    {
    	$zoneId = I('get.zoneId');
    	//正式部署要删除掉具体参数
    	$zoneId = 9;
        $params = array('zoneid'=>$zoneId,
            'layerstyle'=>'geocities',
            'align'=>'right',
            'padding'=>2,
            'closetext'=>'%5BClose%5D'
        );
        $queryParams = http_build_query($params);
        $url = $this->adServerUrl.'/delivery/al.php?'.$queryParams;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $pattern = '/td\s+width=\\\"(?P<width>\d+)\\\"\s+height=\\\"(?P<height>\d+)';
        $pattern .= ".+?href=\\\'(?P<ckStatistics>[^']+)\\\'.+?src=\\\'(?P<img>[^']+)\\\'.+?src=\\\'(?P<showStatistics>[^']+)\\\'/";
        $matches = array();
        $res = array();
        if(preg_match($pattern, $output, $matches)){
            $res = array(
                		'clickUrl'=>$matches['ckStatistics'],
                		'imgUrl'=>$matches['img'],
               			'callBackParam'=>$matches['showStatistics'],
            			'width'=>$matches['width'],
            			'height'=>$matches['height']
            	);
        }
        //echo '<pre/>';
        //print_r($res);
        if($res){
            echo json_encode($res);
        }else{
            echo json_encode(array('code'=>0,'msg'=>'广告已停用'));
        }
        
    }
    /**
     * @desc 视频广告
     * @return mixed[]|mixed
     */
    public function video()
    {
    	$zoneId = I('get.zoneId');
    	//正式部署要删除掉具体参数
    	$zoneId = 13;
        $params = array(
            'script'=>'bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml',
            'format'=>'vast',
            'nz'=>1,
            'zones'=>"pre-roll=$zoneId"
        );
        $queryParams = http_build_query($params);
        $queryParams = str_replace('%3A', ':', $queryParams);
        $url = $this->adServerUrl.'/delivery/fc.php?'.$queryParams;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $object = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $object = $object->Ad->InLine;
        $arr = json_decode(json_encode($object), true);
        $res = array();
        if($arr){
            $res = array(
                //'title' => $arr['AdTitle'],
                //'description' => $arr['Description'],
                'callBackParam' => $arr['Impression']['URL'],
                //'videoDuration' => $arr['Video']['Duration'],
                'clickUrl' => $arr['Video']['VideoClicks']['ClickThrough']['URL'],
                //'bitrate' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['bitrate'],
                'Width' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['width'],
                'Height' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['height'],
                'videoAttrType' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['type'],
                'videoUrl' => $arr['Video']['MediaFiles']['MediaFile']['URL'],
                //'trackingEventUrlStart' => $arr['TrackingEvents']['Tracking']['0']['URL'],
                //'trackingEventUrlMidpoint' => $arr['TrackingEvents']['Tracking']['1']['URL'],
                //'trackingEventUrlFirstQuartile' => $arr['TrackingEvents']['Tracking']['2']['URL'],
                //'trackingEventUrlThirdQuartile' => $arr['TrackingEvents']['Tracking']['3']['URL'],
                //'trackingEventUrlComplete' => $arr['TrackingEvents']['Tracking']['4']['URL'],
                //'trackingEventUrlMute' => $arr['TrackingEvents']['Tracking']['5']['URL'],
                //'trackingEventUrlPause' => $arr['TrackingEvents']['Tracking']['6']['URL'],
                //'trackingEventUrlReplay' => $arr['TrackingEvents']['Tracking']['7']['URL'],
                //'trackingEventUrlFullscreen' => $arr['TrackingEvents']['Tracking']['8']['URL'],
                //'trackingEventUrlStop' => $arr['TrackingEvents']['Tracking']['9']['URL'],
                //'trackingEventUrlUnmute' => $arr['TrackingEvents']['Tracking']['10']['URL'],
                //'trackingEventUrlResume' => $arr['TrackingEvents']['Tracking']['11']['URL'],
            );
        }
        //echo "<pre/>";
        //print_r($res);exit;
        if($res){
            echo json_encode($res);
        }else{
            echo json_encode(array('code'=>0,'msg'=>'广告已停用'));
        }
    }
    /**
     * @desc 悬浮视频素材广告
     * @return json
     */
    public function suVideo(){
    	$zoneId = I('get.zoneId');
    	//正式部署要删除下面的变量
    	$zoneId = 12;
    	$params = array(
    			'script'=>'bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml',
    			'format'=>'vast',
    			'nz'=>1,
    			'zones'=>'pre-roll='.$zoneId,
    	);
    	$queryParams = http_build_query($params);
    	$queryParams = str_replace('%3A', ':', $queryParams);
    	$url = $this->adServerUrl.'/delivery/fc.php?'.$queryParams;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	$output = curl_exec($ch);
    	curl_close($ch);
    	$val = json_decode(json_encode(simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	//$arr['title'] = $val['Ad']['InLine']['AdTitle'];
    	$res = array();
    	$res['callBackParam'] = $val['Ad']['InLine']['Impression']['URL'];
    	$res['adImg'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['@attributes'];
    	$res['img_url'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['URL'];
    	$res['clickUrl'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['NonLinearClickThrough']['URL'];
    	//echo "<pre/>";
    	//print_r($res);
    	if($res){
    	    echo json_encode($res);
    	}else{
    	    echo json_encode(array('code'=>0,'msg'=>'广告已停用'));
    	}
    }
    //接口请求文字广告
    public function text()
    {
    	$zones = I('get.zoneId');
        $zones = 10;
        $prefix = 'revive-0-';
        $loc = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
        $url = $this->adServerUrl.'/delivery/asyncspc.php?zones='.$zones.'&prefix='.$prefix.'&loc='.$loc;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($output,true);
        $html = $arr[$prefix.'0']['html'];
        
        $matches = array();
        if(preg_match("/href='(?P<ckStatistics>[^']+)'.+?>(?P<text>[^<]+).+?src='(?P<showStatistics>[^']+)/", $html, $matches)){
            $arr[$prefix.'0']['ckStatistics'] = $matches['ckStatistics'];
            $arr[$prefix.'0']['text'] = $matches['text'];
            $arr[$prefix.'0']['showStatisticsUrl'] = $matches['showStatistics'];
        }
        $res = array();
        $res['clikcUrl'] = $arr[$prefix.'0']['ckStatistics'] = $arr[$prefix.'0']['ckStatistics'];
        $res['callBackParam'] = $arr[$prefix.'0']['showStatisticsUrl'];
        $res['text'] = $arr[$prefix.'0']['text'];
        $res['length'] = mb_strlen($res['text'], 'utf8');
        //echo "<pre/>";
        //print_r($res);
        if($res){
            echo json_encode($res);
        }else{
            echo json_encode(array('code'=>0,'msg'=>'广告已停用'));
        }
    }
    //回调接口
    function callBack(){
    	$param = I('post.callBackParam');
    	$token = I('post.token');
    	$header = array("x-access-token:$token");
    	$header = array("x-access-token:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjViOWI0OTA2ZWI5MjAwNTg4MGUyMGNkZiIsImlhdCI6MTUzNzI2MjI4OSwiZXhwIjoxNTM3MjY1ODg5fQ.GRizHT0uQKBsYz-UGGf67YrNl1ZQcQbyg__dIIE7o0Q");
    	//测试地址
    	$url = 'http://101.132.158.59:3000/token_api/validate_token';
    	$outPut = $this->httpPost($url,$header);
    	$res = json_decode($outPut,true);
    	if($res['code']===1){
    		$param = 'http://localhost:8000/www/delivery/lg.php?bannerid=2&campaignid=2&zoneid=2&loc=http%3A%2F%2Flocalhost%2Fdemo.html&cb=dcf03d82cc';
    		$this->imgGet($param);
    		$msg = array('code'=>1,'msg'=>'回调成功');
    		echo json_encode($msg);die;
    	}else{
    		$msg = array('code'=>0,'msg'=>'无效token');
    		echo json_encode($msg);
    	}
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