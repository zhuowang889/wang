<?php
namespace Api\Model;

use Think\Model;

class AdminModel extends Model{
    private $userInfos;
    private $loginExpire = 1800;
    private $SSOpre = '';
    
    /**
     * user 验证用户名密码
     * @param string $user_name
     * @param string $password
     * @return int
     */
    public function checkPassword($user_name='', $password='') {
        if(!$user_name){
            $user_name = I('post.user_name');
            $password = I('post.password');
        }
        $passwordMd5 = $this->encriptPassword($password);
        if($arrId = $this->where("`user_name`='{$user_name}' AND `password`='{$passwordMd5}'")->getField('id', 1)){
            return $arrId['id'];
        }else{
            return 0;
        }  
    }
    
    /**
     * user 判断是否登陆
     * @param string $ssoKey
     * @param number $uid
     * @return string
     */
    public function checkLogin($ssoKey = '', $uid = 0){
        if($uid && !$ssoKey){
            $user_name = $this->getUserinfo($uid, 'user_name');
            $ssoKey = $this->getSSOkey($user_name, $uid);
        }
        if($ssoKey){
            if($uInfo = S($ssoKey)){
                return $uInfo;
            }else{
                return '';
            }
        }
    }
    
    /**
     * user 设置SSO登陆
     * @param int $uid
     * @return string|bool
     */
    public function setUserSSO(int $uid){
        $user_name = $this->getUserinfo($uid, 'user_name');
        
        $key = $this->getSSOkey($user_name, $uid);
        $res = json_encode(array('id'=>$uid, 'user_name'=>$user_name));
        if(S($key, $res, ['expire'=>$this->loginExpire, 'data_cache_prifix'=>$this->SSOpre])){
        	//后期加上的cookie,完善单点登录
        	cookie('ssouser',$key,0);
        	//cookie end
            return $key;
        }else{
            return false;
        }
    }
    
    /**
     * user 获取sso key
     * @param string $user_name
     * @param string $uid
     * @return string
     */
    private function getSSOkey($user_name, $uid){
        return substr(md5($user_name.'_'.$uid), 0, 10);
    }

    /**
     * user password encript
     * @param string $password
     * @param string $salt
     * @todo salt
     * @return string
     */
    private function encriptPassword($password, $salt = ''){
        return md5($password);
    }
    
    /**
     * uid 通过SSOkey 获取
     * @param string $SSOkey
     * @return int
     */
    public function getUidFromSSOkey($SSOkey){
        if($uInfo = S($SSOkey)){
            return $uInfo['id'];
        }else{
           return 0; 
        }
    }
    
    /**
     * user 通过id获取用户信息
     * @param int $uid
     * @param string $field
     * @return NULL|array|string|int
     */
    public function getUserinfo(int $uid, $field = '') {
        if(isset($this->userInfos[$uid])){
            if($field){
                return $this->userInfos[$field];
            }
            return $this->userInfos[$uid];
        }else{
            $fields = ['id','user_name','lastlogin_time','status'];
            if($field) $fields = $field;
            $userInfos = $this->where("`id`='{$uid}'")->field($fields)->find();
            if($field){
                return $userInfos[$field];
            }else{
                $this->userInfos = $userInfos;
                return $this->userInfos;
            }
        }
    }
    
    public function get($param) {
        return $this->{$param};
    }
}
