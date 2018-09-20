<?php
namespace Sso\Model;

class AuthGroupAccessModel extends BaseModel
{
    protected $tableName = 'auth_group_access';
    
    /**
     * @desc 判断用户是否为超级用户
     * @author zcj
     * @time 2018.9.19
     */
    public function checkUser($userId)
    {
    	$groupId = $this->where(['uid'=>$userId])->getField('group_id');
    	return $groupId;
    }
    
    /**
     * 获取用户所有权限
     * @author luduoliang <luduoliang@imohoo.com> (2016/12/02)
     */
    public function getUserRules($user_id)
    {
        $where = array(
            'a.uid' => $user_id,
        );
        $join = 'LEFT JOIN '.C(DB_PREFIX).'auth_group b ON b.id=a.group_id';
        $rules = $this->alias('a')
                      ->where($where)
                      ->join($join)
                      ->field('b.rules')
                      ->select();
       
        if(!$rules){
            return array();
        }
        
        $rules_str = '';
        foreach($rules as $v){
            $rules_str .= $v['rules'] . ',';
        }
        
        $rules_str = rtrim($rules_str, ',');

        $rules_arr = array_unique(explode(',', $rules_str));
        
        $admin_menu_model = new MenuModel();
        $menus = $admin_menu_model->getMenus($rules_arr);
        
      
        $menus = get_column($menus, 2);
        
        //dump($menus);exit;
        return $menus;
        
    }
}