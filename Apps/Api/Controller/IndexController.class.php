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
        $object = simplexml_load_string($output);
        echo $object->Ad->Inline->Impression;
        $rsJson = json_encode(\xml2array($object));
        echo <<<EOT
<script>
var mystr = '{$rsJson}',
myjson = JSON.parse(mystr);
</script>
EOT;
        print_r($object);exit;
        return $output;
    }
    
}