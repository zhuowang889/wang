<?php
namespace Api\Controller;
use Think\Controller;

class IndexController extends Controller {
    public $adServerUrl = 'http://101.132.106.202/www';
	//接口请求广告
    public function index()
    {
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
    	//var_dump($output);die;
    	curl_close($ch);
    	$arr = json_decode($output,true);
    	$html = $arr[$prefix.'0']['html'];
    	//echo $html;
    	$matches = [];
    	if(preg_match("/href='([^']+)'.+?src='([^']+)'.+?src='([^']+)'/", $html, $matches)){
    	    $arr[$prefix.'0']['url'] = $matches[1];
    	    $arr[$prefix.'0']['img1'] = $matches[2];
    	    $arr[$prefix.'0']['img2'] = $matches[3];
    	    unset($arr[$prefix.'0']['html']);
    	    $output = json_encode($arr);
    	}
    	print_r($arr);
    	return $output;
    }
    /**
     * DHTML广告
     * @return mixed[]|mixed
     */
    public function dhtmlAd()
    {
        $params = array('zoneid'=>9,
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
        //echo $output;
        $pattern = '/td\s+width=\\\"(?P<width>\d+)\\\"\s+height=\\\"(?P<height>\d+)';
        $pattern .= ".+?href=\\\'(?P<ckStatistics>[^']+)\\\'.+?src=\\\'(?P<img>[^']+)\\\'.+?src=\\\'(?P<showStatistics>[^']+)\\\'/";
        //.+?src='([^']+)'
        $matches = [];
        if(preg_match($pattern, $output, $matches)){
            $rs = array('width'=>$matches['width'],
                'height'=>$matches['height'],
                'ckStatisticsUrl'=>$matches['ckStatistics'],
                'img'=>$matches['img'],
                'showStatisticsUrl'=>$matches['showStatistics']
            );
            echo "<pre/>";
            print_r($rs);
            return $rs;
        }
        return $output;
    }
    /**
     * @desc 视频广告
     * @return mixed[]|mixed
     */
    public function video()
    {
        $params = array(
            'script'=>'bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml',
            'format'=>'vast',
            'nz'=>1,
            'zones'=>'pre-roll=13'
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
        //print_r($arr);exit;
        $rsArr = array(
            'title' => $arr['AdTitle'],
            'description' => $arr['Description'],
            'impressionUrl' => $arr['Impression']['URL'],
            'videoDuration' => $arr['Video']['Duration'],
            //'videoAdId' => $arr['Video']['AdId'],
            'videoClickThroughUrl' => $arr['Video']['VideoClicks']['ClickThrough']['URL'],
            //'videoAttrDelivery' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['delivery'],
            'videoAttrBitrate' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['bitrate'],
            'videoAttrWidth' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['width'],
            'videoAttrHeight' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['height'],
            'videoAttrType' => $arr['Video']['MediaFiles']['MediaFile']['@attributes']['type'],
            'videoUrl' => $arr['Video']['MediaFiles']['MediaFile']['URL'],
            'trackingEventUrlStart' => $arr['TrackingEvents']['Tracking']['0']['URL'],
            'trackingEventUrlMidpoint' => $arr['TrackingEvents']['Tracking']['1']['URL'],
            'trackingEventUrlFirstQuartile' => $arr['TrackingEvents']['Tracking']['2']['URL'],
            'trackingEventUrlThirdQuartile' => $arr['TrackingEvents']['Tracking']['3']['URL'],
            'trackingEventUrlComplete' => $arr['TrackingEvents']['Tracking']['4']['URL'],
            'trackingEventUrlMute' => $arr['TrackingEvents']['Tracking']['5']['URL'],
            'trackingEventUrlPause' => $arr['TrackingEvents']['Tracking']['6']['URL'],
            'trackingEventUrlReplay' => $arr['TrackingEvents']['Tracking']['7']['URL'],
            'trackingEventUrlFullscreen' => $arr['TrackingEvents']['Tracking']['8']['URL'],
            'trackingEventUrlStop' => $arr['TrackingEvents']['Tracking']['9']['URL'],
            'trackingEventUrlUnmute' => $arr['TrackingEvents']['Tracking']['10']['URL'],
            'trackingEventUrlResume' => $arr['TrackingEvents']['Tracking']['11']['URL'],
        );
        echo "<pre/>";
        print_r($rsArr);exit;
        $rsJson = json_encode($rsArr);
        return $rsJson;
    }
    /**
     * @desc 悬浮视频素材广告
     * @return json
     */
    public function suVideo(){
    	$zone_id = I('get.zone_id');
    	//正式部署要删除下面的变量
    	$zone_id = 12;
    	$params = array(
    			'script'=>'bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml',
    			'format'=>'vast',
    			'nz'=>1,
    			'zones'=>'pre-roll='.$zone_id,
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
    	$arr['title'] = $val['Ad']['InLine']['AdTitle'];
    	$arr['impression_url'] = $val['Ad']['InLine']['Impression']['URL'];
    	$arr['adImg'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['@attributes'];
    	$arr['adImg_url'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['URL'];
    	$arr['click'] = $val['Ad']['InLine']['NonLinearAds']['NonLinear']['NonLinearClickThrough']['URL'];
    	return json_encode($arr);
    }
    //接口请求广告
    public function text()
    {
        $zones = 10;
        $prefix = 'revive-0-';
        $loc = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
        //http://101.132.106.202/www/delivery/asyncspc.php?zones=10&prefix=revive-0-&loc=http%3A%2F%2Flocalhost%2Fdemo.html
        $url = $this->adServerUrl.'/delivery/asyncspc.php?zones='.$zones.'&prefix='.$prefix.'&loc='.$loc;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($output,true);
        $html = $arr[$prefix.'0']['html'];
        //echo $html;
        $matches = [];
        
        if(preg_match("/href='(?P<ckStatistics>[^']+)'.+?>(?P<text>[^<]+).+?src='(?P<showStatistics>[^']+)/", $html, $matches)){
            $arr[$prefix.'0']['ckStatistics'] = $matches['ckStatistics'];
            $arr[$prefix.'0']['text'] = $matches['text'];
            $arr[$prefix.'0']['showStatisticsUrl'] = $matches['showStatistics'];
            unset($arr[$prefix.'0']['html']);
            $output = json_encode($arr);
        }
        print_r($arr);
        echo "<pre/>";
        return $output;
    }
    
}