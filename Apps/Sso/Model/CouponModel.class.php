<?php
namespace Sso\Model;
/**
 * @desc 优惠券
 * @author zcj
 */
class CouponModel extends BaseModel
{
    protected $tableName = 'Coupon_tmp';
    //经营活动  优惠券管理
    public function mlist($res){
    	$p = $res['p']?$res['p']:1;
 		$result = $this->where('isdel=0')->page($p.',2')->select();
 		$count  = $this->where('isdel=0')->count();
 		$pages = ceil($count/2);
    	$page   = new \Think\Page($count,5);
    	$show       = $page->show();
    	$arr = [];
    	$arr['res'] = $result;
    	$arr['show'] = $show;
    	$arr['pages'] = $pages;
    	return $arr;
    }
    //优惠券模板编辑
    public function edit($res){
    	$id = $res['id'];
    	$data = $this->where('id='.$id)->find();
    	return $data;
    }
    //优惠券模板逻辑删除
    public function del($id){
    	$data = [];
    	$data['isdel'] = 1;
    	$data['id'] = $id;
    	$bool = $this->save($data);
    	return $bool;
    }
}