<?php
namespace Sso\Model;

class AdminModel extends BaseModel
{
	protected $tableName = 'admin';
    
	/**
	 * @desc 单个用户查询
	 * @author zcj
	 * @time 2018.9.19
	 */  
	public function singleUser($userId){
		$where = [];
		$where['id'] = array('eq',$userId);
		$where['status'] = array('neq',2);
		$userInfo = $this->where($where)->select();
		$arr = [];
		$arr['list'] = $userInfo;
		return $arr;
	}
	/**
	 * @desc 完善用户资料
	 * @author zcj
	 * @time 2018.9.20
	 */
	public function perfectInfor()
	{
		$data = [];
		$where = [];
		$data['phone'] = I('post.phone')?I('post.phone'):'0';
		$data['email'] = I('post.email')?I('post.email'):'无';
		$data['addr'] = I('post.addr')?I('post.addr'):'无';
		$data['gender'] = I('post.sex')==1?1:0;
		$where['id'] = I('post.id');
		$data['intro'] = I('post.intro')?I('post.intro'):'这个家伙很懒,什么也没有留下';
		$res = $this->where($where)->save($data);
		return $res;
	}
    /**
     * @description:查询用户
     * @author wuyanwen(2016年11月22日)
     */
    public function findUser($name, $pwd)
    {
        $where = array(
            'user_name' => $name,
            'password' => md5($pwd),
            'status'   => parent::NORMAL_STATUS,
        );
        
        $result = $this->where($where)->find();
        
        return $result;
    }
    
    /**
     * 根据id更新用户登录时间
     * @author luduoliang <luduoliang@imohoo.com> (2016/12/01)
     * @param unknown $id
     */
    public function updateLoginTime($id)
    {
        $where = array(
            'id' => $id,
        );
        $saveData = array(
            'lastlogin_time' => time(),
        );
        return $this->where($where)->save($saveData);
    }
    
    /**
     * @description:每页显示数目
     * @author wuyanwen(2016年12月1日)
     * @param unknown $num
     * @return multitype:unknown string
     */
    public function selectAllUser($num=10)
    {
        $where = array(
            'status' => parent::NORMAL_STATUS,
        );
    
        $count      = $this->where($where)->count();
        $page       = new \Think\Page($count,$num);
        $show       = $page->show();
        $list       = $this->where($where)->limit($page->firstRow.','.$page->listRows)->select();
    
        return array('page' => $show , 'list' => $list);
    
    }
    
    /**
     * @description:添加后台用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $data
     * @return boolean
     */
    public function addAdminUser($data)
    {
        return $this->add($data) ? true : false;
    }
    
    /**
     * @description:更新用户信息
     * @author wuyanwen(2016年12月1日)
     * @param unknown $data
     */
    public function editAdminUser($data)
    {
        $where = array(
            'id'    => $data['id'],
        );

        unset($data['id']);
        
        return $this->where($where)->save($data);
    }
    
    /**
     * @description:删除用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $user_id
     * @return Ambigous <boolean, unknown>
     */
    public function deleteAdminUser($user_id)
    {
        $where = array(
            'id' => $user_id,            
        );
        
        $data = array(
            'status' => parent::DEL_STATUS,
        );
        
        return $this->where($where)->save($data);
    }
    
    
    /**
     * @description:根据id查询用户
     * @author wuyanwen(2016年12月1日)
     * @param unknown $user_id
     */
    public function findAdminUserById($user_id)
    {
        $where = array(
            'id'     => $user_id,
            'status' => parent::NORMAL_STATUS,
        );
        
        return $this->where($where)->find();
    }
    
    public function findAdminUserByName($user_name)
    {
        $where = array(
            'user_name' => $user_name,
            'status'    => parent::NORMAL_STATUS,
        );
        
        
        return $this->where($where)->find();
    }
}