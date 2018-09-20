<?php
namespace Sso\Controller;

class UserController extends CommonController
{
    protected $admin_user_model;
    
    public function __construct()
    {
        parent::__construct();
        /* @var $admin_user_model \Admin\Model\UserModel */
        $admin_user_model = D('Admin');

        $this->admin_user_model = $admin_user_model;
    }
    
    /**
     * @description:用户列表
     * @author wuyanwen(2016年12月1日)
     */
    public function index()
    {
        $user_info = $this->admin_user_model->selectAllUser();
        
        $this->assign('user_info',$user_info['list']);
        $this->assign('page',$user_info['page']);
        $this->display();
    }
    
    /**
     * @description:添加用户
     * @author wuyanwen(2016年12月1日)
     */
    public function addUser()
    {
        if(IS_POST){
            
            $user_info = array(
                'user_name'      => I('post.user_name','','trim'),
                'password'       => md5(I('post.password','','trim')),
                'lastlogin_time' => time(),
            );
            
           if($this->admin_user_model->findAdminUserByName($user_info['user_name'])){
               $this->ajaxSuccess('该用户已经被占用');
           }
           
           if($this->admin_user_model->addAdminUser($user_info)){
               $this->ajaxSuccess('添加成功');
           }else{
              $this->ajaxError('添加失败');
           }
        }else{
            $this->display();
        }
    }
    
    
    /**
     * @description:编辑用户
     * @author wuyanwen(2016年12月1日)
     */
    public function editUser()
    {            
        if(IS_POST){
            $user_info = array(
                'user_name' => I('post.user_name','','trim'),
                'id'        => I('post.id','','intval'),
            );
           
           if(I('post.password')){
               $user_info['password'] = md5(I('post.password','','trim'));
           }

           if($this->admin_user_model->editAdminUser($user_info) !== false){
               $this->ajaxSuccess('更新成功');
           }else{
              $this->ajaxError('更新失败');
           }
        }else{
            $user_id = I('get.user_id','','intval');
            $user_info = $this->admin_user_model->findAdminUserById($user_id);
            $this->assign('user_info',$user_info);
            $this->display();
        }
    }
    /**
     * @description:删除用户
     * @author wuyanwen(2016年12月1日)
     */
    public function deleteUser()
    {
        $user_id = I('post.user_id','','intval');
        
        $result = $this->admin_user_model->deleteAdminUser($user_id);
        
        if($result){
            $this->ajaxSuccess("删除成功");
        }else{
            $this->ajaxError("删除失败");
        }
    }
    /**
     * @desc 更新用户资料页展示
     * @author zcj
     * @time 2018.9.19
     */
    public function userFile(){
    	$id = I('get.id');
    	$this->assign('id',$id);
    	$this->display();
    }
    /**
     * @desc 对实际提交数据进行更新
     * @author zcj
     * @time 2018.9.20
     */
    public function updateUserFile(){
    	$res = $this->admin_user_model->perfectInfor();
    	if($res===false){
    		$this->ajaxReturn("更新失败");
    	}else{
    		$this->ajaxReturn("更新成功");
    	}
    }
}