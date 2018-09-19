<?php
//鉴别按钮是否展示
function showButton($menu_name){
	$ruleId = M('auth_rule')->where(array('menu_name'=>$menu_name))->getField('id');
	$userId = session('user_info')['id'];
	$groupId = M('auth_group_access')->where(array('uid'=>$userId))->getField('group_id');
	$rules = M('auth_group')->where(array('id'=>$groupId))->getField('rules');
	if(strpos($rules,$ruleId) !==false){
		//有权限
		return true;
	}else{
		//无权限
		return false;
	}
}
/**
 * 获取管理员权限
 */
function getPrivilege($roleid){
    $res = array();
    if($roleid){
        $key = 'ADMIN_'.$roleid.'_PRIVILEGE';
        //S($key,null);
        if(!S($key)){
            $roleInfo = M('Role')->where(array('id'=>$roleid))->find();
            $privilegeInfo = isset($roleInfo['privilege']) ? unserialize($roleInfo['privilege']):array();
            $modules = isset($privilegeInfo['modules'])?$privilegeInfo['modules']:array();
            $actions = isset($privilegeInfo['actions'])?$privilegeInfo['actions']:array();
            if($modules){
                $modulesInfo = M('node')->where(array('id'=>array('in',implode(',',$modules))))->field('id,name,title')->select();
                if($modulesInfo){
                    foreach($modulesInfo as $k=>$v){
                         $privilege['module'][strtolower($v['name'])] = $v;
                    }
                }
            }
            if($actions){
                $actionsInfo = M('node')->where(array('id'=>array('in',implode(',',$actions))))->field('id,name,title,pid')->select();
                if($actionsInfo){
                    foreach($actionsInfo as $k=>$v){
                        $privilege['action'][$v['pid']][] = strtolower($v['name']);
                    }
                }
            }
            S($key,$privilege);
        }else{
            $privilege = S($key);
        }
        return $privilege;
    }
}

/**
 * 获取用户名
 * @param $mid
 */
function getUserName($mid)
{
    $adminInfo = array();
    if($mid){
        $key = md5('ADMIN_'.$mid);
        if(!S($key)){
            $adminInfo = M('admin')->where("id = {$mid}")->find();
            S($key,$adminInfo);
        }else{
            $adminInfo = S($key);
        }
        if($adminInfo){
            return $adminInfo['realname'];
        }
    }
}



/**
 * 获取影视照
 */
function getFilmImg($uid)
{
    $dir=getAvatarDir($uid);

    $filename = DATA_PATH . '/uploads/avatar/' . $dir . '/film_original.jpg';
    if (file_exists($filename)) {
        $imgUrl = '/data/uploads/avatar/' . $dir . '/film_original.jpg';
    } else {
        $imgUrl = '/data/uploads/avatar/default/film.png';
    }
    return $imgUrl;
}



/**
 *获取广告焦点图片，type
 * db:type[1:首页]
 */
function getAdFocusImages($type = 1)
{
    $key = md5('ad_' . $type . '_images');
    S($key,null);
    $map = array();
    if (!S($key)) {
        $map = array('type' => $type);
     
        $images = M('advertise')->where($map)->field('title,url,photo')->order('`order`')->limit(16)->select();
        S($key, $images);
    } else {
        $images = S($key);
    }
    return $images;
}


/**
 *获取电影信息
 */
function getMovieByid($classid = 0)
{	
	$map['class'] = array(
		'like','%$classid%'
	);
	$str = "class like '%$classid%'";
	$movies  = M('Movie')->where($str)->order("id desc")->limit(0,20)->select();

    return $movies;
}

/**
 *获取新闻信息
 */
function getNewsBycid($cid = 0,$limit=4)
{	
	$map=array('cid' => $cid);
	$news  = M('Article')->where($map)->order("adddate desc")->limit(0,$limit)->select();

    return $news;
}

/**
 * 根据$cityid返回属于该$cityid的所有地区
 * @param int $cityid
 * @return string
 */
function getRegionByFid($cityid = 0)
{
    $key = md5('region' . $cityid);
    if (!S($key)) {
        $regions = M('region')->where(array('fid' => $cityid))->field(array('id,name'))->select();
        S($key, $regions);
    } else {
        $regions = S($key);
    }
    return $regions;
}

function getRegionByid($id){
    $map = array();
    if(is_numeric($id)){
        $map = array('id'=>$id);
    }elseif(is_array($id)){
        $map = array('id'=>array('in',implode(',',$id)));
    }
    $regions = M('region')->where($map)->field(array('id,name'))->select();
    //echo M('region')->_sql();
    return $regions;
}

#获取名称
function getRegionName($id)
{
   $id=$id+0;

   if(!$id) return false;

   $regions = M('region')->where(array('id' => $id))->field(array('id,name'))->find();

    return $regions['name'];
}



/**
 * 根据用户id获取用户信息
 * @param int $uid
 * @return array|string
 */
function getUserInfo($uid = 0)
{
    $userInfo = array();
    if ($uid) {
        $_cacheUserInfoKey = md5('User_' . $uid);
        S($_cacheUserInfoKey, null);
        if (S($_cacheUserInfoKey)) {
            $userInfo = S($_cacheUserInfoKey);
        } else {
            $res = M('Member')->where("userid = $uid")->find();
            if($res){
                $userInfo = $res;
                S($_cacheUserInfoKey, $res);
            }
        }
    }
    return $userInfo;
}


/**
 * 去一个二维数组中的每个数组的固定的键知道的值来形成一个新的一维数组
 * @param $pArray 一个二维数组
 * @param $pKey 数组的键的名称
 * @return 返回新的一维数组
 */
function getSubByKey($pArray, $pKey = "", $pCondition = "")
{
    $result = array();
    foreach ($pArray as $temp_array) {
        if (is_object($temp_array)) {
            $temp_array = (array)$temp_array;
        }
        if (("" != $pCondition && $temp_array[$pCondition[0]] == $pCondition[1]) || "" == $pCondition) {
            $result[] = ("" == $pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
        }
    }
    return $result;
}


/**
 * 后台获取下拉栏目选项
 * @param int $fid
 * @return string
 */
function getSelectCategory($fid = 0)
{
   // import('Common.Libary.tree');
   // $tree = new tree();
	$tree = new \Common\Libary\tree();

    if (!S('Category')) {
        $result = M('Category')->field('id,name,fid,module')->select();
        S('Category', $result);
    } else {
        $result = S('Category');
    }
    $array = array();
    foreach ($result as $r) {
        $r['parentid'] = $r['fid'];
        $r['selected'] = $r['id'] == $fid ? 'selected' : '';
        $array[] = $r;
    }
    $tree->init($array);
    $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
    $tree->init($array);
    $select_categorys = $tree->get_tree(0, $str);
    return $select_categorys;
}

/**
 * 根据栏目id获取栏目名称
 * @param int $cid
 * @return int
 */
function getCategoryNamebyID($cid = 0)
{
    $result = array();
    $categoryName = $cid;
    if (!S('Category')) {
        $result = M('Category')->field('id,name,fid,module')->select();
        S('Category', $result);
    } else {
        $result = S('Category');
    }
    if ($result) {
        foreach ($result as $val) {
            if ($val['id'] == $cid) {
                $categoryName = $val['name'];
                break;
            }
        }
    }
    return $categoryName;
}



/**
 * 获取栏目广告 4为banner 3 为left
 * @param int $cid
 */
function getCategoryAd($cid = 0)
{
    $category = array();
    $key = md5('CategoryAd_' . $cid);
    //S($key,null);
    if (!S($key)) {
        $map = array(
            'cid' => $cid,
            'type' => array('in', array('3', '4'))
        );
        $categoryAds = M('advertise')->where($map)->field('cid,type,title,url,photo')->select();
        if ($categoryAds) {
            foreach ($categoryAds as $ad) {
                if ($ad['type'] == 4) {
                    $category['banner'] = $ad;
                } elseif ($ad['type'] == 3) {
                    $category['left'][] = $ad;
                }
            }
            S($key, $category);
        } else {
            S($key, $category);
        }
    } else {
        $category = S($key);
    }
    return $category;
}


/**
 * 获取最新加入的会员
 */
function getNewMember()
{
    $key = 'newMember';
    S($key,null);
    $res = array();
    if (!S($key)) {
        $res = M('Member')->field('userid,username,realname')->where(array('status'=>1))->order('regdate desc')->limit(20)->select();
        // echo M('Member')->_sql();
        if ($res) {
            S($key, $res);
        }
    } else {
        $res = S($key);
    }
    return $res;
}

/*反解加密串*/
function parse($val)
{
    return unserialize(base64_decode($val));
}

/*加密字符串*/
function encrypt($val)
{
    return base64_encode(serialize($val));
}

/**
 * 解析才艺
 */
function parseTalent($v, $t = '/',$c=1)
{

    $caiyi = C('caiyi');
    $str = $_str ='';
    if ($v == '') $v = array();
    $tmp = explode(',', $v);
    $count = count($tmp);
    //($tmp);
    if ($count) {
        for ($i = 0; $i < 4; $i++) {
            if (isset($caiyi[$tmp[$i]])) {
                if (4 == $i + 1) {
                    $_str .= $caiyi[$tmp[$i]];
                } else {
                    $_str .= $caiyi[$tmp[$i]] . $t;
                }
            }
        }


        if($c){
            $str = $_str;
        }else{
            $strLen = strlen($_str);
            $str = "<em title={$_str} style='cursor:pointer'>";
            $str .= $strLen>20 ? substr($_str,0,20):$_str;
            $str .= '</em>';
        }
    } else {
        $str = '暂无才艺';
    }
    echo $str;
}



// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

function parseRegionByID($id){
    $allRegion = array();
    $key = 'cache_regions';
    if(!S($key)){
        $res = M('region')->order('id')->select();
        if($res){
            foreach($res as $key=>$val){
                $allRegion[$val['id']] = $val['name'];
            }
        }
        S($key,$allRegion);
    }else{
        $allRegion = S($key);
    }
    return isset($allRegion[$id])?$allRegion[$id]:'未知';
}

/**
 * 删除目录
 * @param $dir
 * @return bool
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . "/" . $item)) {
            chmod($dir . "/" . $item, 0777);
            if (!deleteDirectory($dir . "/" . $item)) return false;
        };
    }
    return rmdir($dir);
}



function safe($text,$type='all'){

    //无标签格式
    $text_tags	=	'';

    //只存在字体样式
    $font_tags	=	'<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';

    //标题摘要基本格式
    $base_tags	=	$font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';

    //兼容Form格式
    $form_tags	=	$base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';

    //内容等允许HTML的格式
    $html_tags	=	$base_tags.'<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed>';

    //专题等全HTML格式
    $all_tags	=	$form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';

    //过滤标签
    $text	=	strip_tags($text, ${$type.'_tags'} );

    //过滤攻击代码
    if($type!='all'){
        //过滤危险的属性，如：过滤on事件lang js
        while(preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i',$text,$mat)){
            $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
        }
        while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
            $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
        }
    }
    return $text;
}

function nl2br_revert($string) {
    return preg_replace('`<br(?: /)?>([\\n\\r])`', '$1', $string);
}



function getImageInfo($img) {
    $imageInfo = getimagesize($img);
    if ($imageInfo !== false) {
        $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
        $imageSize = filesize($img);
        $info = array(
            "width" => $imageInfo[0],
            "height" => $imageInfo[1],
            "type" => $imageType,
            "size" => $imageSize,
            "mime" => $imageInfo['mime']
        );
        return $info;
    } else {
        return false;
    }
}

 function water($source, $info, $savename=null,$isshow=0, $alpha=100)
 {
    $Font       ="simhei.ttf";
    $TextColor  ="#1FCCDB";

    //检查文件是否存在
    if (!file_exists($source))
        return false;

    //原始图片信息
    $sInfo = getImageInfo($source);
    $type  =$sInfo['type'];
    $s_w   =$sInfo["width"];
    $s_h   =$sInfo['height'];


    //建立图像
    $sCreateFun = "imagecreatefrom" . $sInfo['type'];
    $sImage = $sCreateFun($source);

    if($info['w1'] AND file_exists($info['w1']))
    {
        $water=$info['w1'];

        $wInfo  = getImageInfo($info['w1']);
        $w_w    = $wInfo['width'];
        $w_h    = $wInfo['height'];

        $wCreateFun = "imagecreatefrom" . $wInfo['type'];
        $wImage = $wCreateFun($water);

        //设定图像的混色模式
        imagealphablending($wImage, true);

        //左上角
        $posX = (310-$w_w)/2;
        $posY = (450-$w_h)/2;
        //生成混合图像
        imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $w_w, $w_h, $alpha);
    }


    if($info['w2'] AND file_exists($info['w2']))
    {
        $wInfo  = getImageInfo($info['w2']);
        $w_w    = $wInfo['width'];
        $w_h    = $wInfo['height'];

        $water=$info['w2'];

        $wCreateFun = "imagecreatefrom" . $wInfo['type'];
        $wImage = $wCreateFun($water);

        //设定图像的混色模式
        imagealphablending($wImage, true);

        //居中
        $posX = ($s_w - $w_w) / 2+2;
        $posY = (450-$w_h);

        //生成混合图像
        imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $w_w, $w_h, $alpha);
    }

    if($info['w3'] AND file_exists($info['w3']))
    {
        $wInfo  = getImageInfo($info['w3']);
        $w_w    = $wInfo['width'];
        $w_h    = $wInfo['height'];

        $water=$info['w3'];

        $wCreateFun = "imagecreatefrom" . $wInfo['type'];
        $wImage = $wCreateFun($water);

        //设定图像的混色模式
        imagealphablending($wImage, true);

        //居右
        $posX = $s_w - $w_w-10;
        $posY = 0+2;

        //生成混合图像
        imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $w_w, $w_h, $alpha);
    }

    $color = imagecolorallocate($sImage,hexdec(substr($TextColor,1,2)),hexdec(substr($TextColor,3,2)),hexdec(substr($TextColor,5,2)));

    //no
    if($info['no'])
    {
        //no
        $Text=$info['no'];
        $x = 100;
        $y = 492;

        imagettftext($sImage,14,0,$x, $y,$color,"/vhost/8/9/2/net89200474/www/getmodelpic/".$Font,$Text);
    }

    //生日
    if($info['birthday'])
    {
        //no
        $Text=$info['birthday'];
        $x = 250;
        $y = 492;
        imagettftext($sImage,14,0,$x, $y,$color,"/vhost/8/9/2/net89200474/www/getmodelpic/".$Font,$Text);
    }

    //身高
    if($info['height'])
    {
        $Text=$info['height']."CM";
        $x = 410;
        $y = 492;
        imagettftext($sImage,14,0,$x, $y,$color,"/vhost/8/9/2/net89200474/www/getmodelpic/".$Font,$Text);
    }

    //居住地
    if($info['address'])
    {
        //address
        $Text=$info['address'];
        $x = 567;
        $y = 492;
        imagettftext($sImage,12,0,$x, $y,$color,"/vhost/8/9/2/net89200474/www/getmodelpic/".$Font,$Text);
    }

    if($info['editdate'])
    {
        //editdate
        $Text=$info['editdate'];
        $x = 787;
        $y = 492;
        imagettftext($sImage,14,0,$x, $y,$color,"/vhost/8/9/2/net89200474/www/getmodelpic/".$Font,$Text);
    }

    //输出图像
    $ImageFun = 'Image' . $sInfo['type'];
    //如果没有给出保存文件名，默认为原图像名
    if (!$savename) {
        $savename = "new".$source;
        //@unlink($source);
    }

    if($isshow==0)
    {
        ob_clean();
        header("Content-type: image/" . $type);

        //保存图像
        $ImageFun($sImage);
    }else
    {
        $ImageFun($sImage, $savename);
    }

    imagedestroy($sImage);
}

// 循环创建目录
function mk_dir($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}


function get_domain()
{
	$protocol=(isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		/* 端口 */
		if (isset($_SERVER['SERVER_PORT']))
		{
			$port = ':' . $_SERVER['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
			{
				$port = '';
			}
		}
		else
		{
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}


function yc_phone($str)
{  
    $str=$str;  
    $resstr=substr_replace($str,'****',3,4);  
    return $resstr;  
}  


function yc_name($str)
{
	$str = mb_substr($str, 0, 1, 'utf-8').'***'. mb_substr($str, -1, 1, 'utf-8');
	return $str;
}
