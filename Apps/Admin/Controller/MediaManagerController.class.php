<?php
namespace Admin\Controller;
use Admin\Model\CouponModel;
use Admin\Model\MuserModel;
/**
 * @desc:优惠券管理
 * @author zcj
 * @time:2018.6.18 
 */
class MediaManagerController extends CommonController
{
    //广告位管理
    public function index()
    {
    	$res = I();
        $list = D('Coupon')->mlist($res);
        $this->assign('list',$list['res']);
        $this->assign('page',$list['show']);
        $this->assign('pages',$list['pages']);
        $this->display();
    }
    //添加优惠券模版
    public function add()
    {	
    	$img = $_FILES['mcontent'];
    	if(IS_POST){
	    	$upload = new \Think\Upload();// 实例化上传类
	    	$upload->maxSize   = 3145728 ;// 设置附件上传大小
	    	$upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    	$upload->rootPath  = './Public/'; // 设置附件上传根目录
	    	$upload->savePath  = 'upload/'; // 设置附件上传（子）目录
	    	// 上传文件
	    	$info   =   $upload->uploadOne($img);
	    	
	    	$data = [];
	    	if(!$info) {// 上传错误提示错误信息
	    		var_dump($upload->getError());
	    		echo json_encode(array('status' => 'error','msg' => $upload->getError()));
	    		exit;
	    	}else{// 上传成功
	    		$imgpath = $info['savepath'].$info['savename'];
	    		$data['mtitle'] = I('post.mtitle');
	    		$data['val'] = I('post.val');
	    		$data['mstyle'] =  $imgpath;
	    		M('Coupon_tmp')->data($data)->add();
	    		$this->redirect('Coupon/index');
	    		//echo json_encode(array('status' => 'success','url'=>'/Public/'.$imgpath));
	    		exit;
	    	}
    	}
		$this->display();	
    }
    //优惠券发放
    public function issue(){
    	$users = D('Muser')->mlist($res = null);
    	if(IS_POST){
    		$data = [];
    		$data['uid'] = I('post.uid');
    		$data['mid'] = I('post.mid');
    		$result = M('coupon_muser')->data($data)->add();
    		if($result){
    			//$this->success('发放成功','/Coupon/index',1);
    			$this->redirect('Coupon/index');
    			die;
    		}else{
    			$this->error('发放失败');
    			die;
    		}
    	}
    	$this->assign('users',$users['res']);
    	$this->display();
    }
    //优惠券模板编辑
    public function edit(){
    	$res = I();
    	$result = D('Coupon')->edit($res);
    	$img = $_FILES['mcontent'];
    	if(IS_POST && $img['name']){
    		$upload = new \Think\Upload();// 实例化上传类
    		$upload->maxSize   = 3145728 ;// 设置附件上传大小
    		$upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    		$upload->rootPath  = './Public/'; // 设置附件上传根目录
    		$upload->savePath  = 'upload/'; // 设置附件上传（子）目录
    		// 上传文件
    		$info   =   $upload->uploadOne($img);
    	
    		$data = [];
    		if(!$info) {// 上传错误提示错误信息
    			var_dump($upload->getError());
    			echo json_encode(array('status' => 'error','msg' => $upload->getError()));
    			exit;
    		}else{// 上传成功
    			$imgpath = $info['savepath'].$info['savename'];
    			$data['id'] = $res['id'];
    			$data['mtitle'] = I('post.mtitle');
    			$data['val'] = I('post.val');
    			$data['mstyle'] =  $imgpath;
    			M('Coupon_tmp')->save($data);
    			$this->redirect('Coupon/index');
    			//echo json_encode(array('status' => 'success','url'=>'/Public/'.$imgpath));
    			exit;
    		}
    	}else if(I('post.mtitle') || I('post.val')){
    		if(I('post.mtitle')){
    			$data['mtitle'] = I('post.mtitle');
    		}
    		if(I('post.val')){
    			$data['val'] = I('post.val');
    		}
    		$data['id'] = $res['id'];
    		$bool = M('Coupon_tmp')->save($data);
    		if($bool){
    			//编辑成功
    			$this->redirect('Coupon/index');
    		}else{
    			//编辑失败
    			$this->redirect('Coupon/edit');
    		}
    		die;
    	}
    	$this->assign('res',$result);
    	$this->display();
    }
    //模板删除
    public function del(){
    	$id = I('get.id');
    	$bool = D('Coupon')->del($id);
    	if(!$bool){
    		//赶进度，这个提示消息暂不去写
    		$this->error('删除失败');
    		//加了下面一行是因为删除之后无法看到效果,需要刷新页面
    		$this->redirect('index');
    	}else{
    		$this->success('删除成功');
    		//加了下面一行是因为删除之后无法看到效果,需要刷新页面
    		$this->redirect('index');
    	}
    }
}