<?php
namespace Common\Libary;
/**
 * 金融客户
 */
class sms_jrkh {
	 public $url = "http://qxt.fungo.cn/Recv_center";
	 public $CpName = "bkswhy";
	 public $CpPassword = "bk0607";
	 public function curlGet($url) {	
		//echo "<br>".$url."<br>";
		$ch = curl_init ();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,$agent);  
        curl_setopt($ch, CURLOPT_TIMEOUT,6); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        //echo "<br>ret=".$ret."<br>";
		if (false == $ret) 
		{
            $result = array('status'=>3,'msg'=> '网络错误');
        } 
		else 
		{            
            $ret = json_decode($ret,true);
            if ($ret['code'] == '0') {
                $result = array('status'=>1,'msg'=> '');
            } else {
                $result = array('status'=>3,'msg'=> '');
            }
        }
		curl_close ( $ch );
		return $result;
	}
	
	function curl_post_https($data) { // 模拟提交数据函数		
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $this->url); // 要访问的地址

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		//echo "<br> data = " . $data . "<br>";
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包

		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$ret = curl_exec($curl); // 执行操作
		//echo "<br> ret = " . $ret . "<br>";

		$ret = curl_exec ( $ch );
        
		curl_close($curl); // 关闭CURL会话
		return $ret; // 返回数据，json格式
	}

	public function sendSMS( $mobile, $msg) {		
		$params = "CpName=".$this->CpName."&CpPassword=".$this->CpPassword."&DesMobile=".$mobile."&Content=".$msg."&ExtCode=";
		$result = $this->curl_post_https($params);
		return $result;
	}

}
?>