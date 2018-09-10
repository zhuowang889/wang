<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>广告位置管理</title>
		<link rel="stylesheet" href="/Public/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="/Public/css/global.css" media="all">
		<link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css">
		<script type="text/javascript" src="/Public/plugins/picker/WdatePicker.js"></script>
		<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
	</head>

	<body>
		<div class="admin-main">
		<?php if(showButton('MediaManager/add')): ?><blockquote class="layui-elem-quote">
		<a href="<?php echo U('MediaManager/add');?>">
				<button  class="layui-btn layui-btn-small add">
					<i class="layui-icon">&#xe608;</i> 添加媒体数据
				</button>
				</a>
			</blockquote><?php endif; ?>
			<fieldset class="layui-elem-field">
				<table class="layui-table">
					  <thead>
					    <tr>
					      <th>媒体ID</th>
					      <th>媒体名称</th>
					      <th>媒体网址</th>
					      <th>描述</th>
					      <?php if(showButton('MediaManager/edit')){ ?>
					      <th>操作</th>
					      <?php }?>
					    </tr> 
					  </thead>
					  <tbody>
						<?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr>
						      <td><?php echo ($vo["id"]); ?></td>
						      <td><?php echo ($vo["mtitle"]); ?></td>
						      <td><?php echo ($vo["val"]); ?></td>
						      <td><img width="50px" height="50px" src="/Public/<?php echo $vo['mstyle'];?>"></td>
						      <td>
						      <?php if(showButton('MediaManager/edit')): ?><a href="<?php echo U('Admin/MediaManager/edit/id/'.$vo['id'].'/p/'.I('get.p'));?>"><button class="layui-btn">编辑</button></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
						    </tr><?php endforeach; endif; ?>
					  </tbody>
				</table>
				</div>
			</fieldset>
			<div class="admin-table-page">
				<div id="page" class="page">
				<center><?php echo ($page); ?></center>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="/Public/plugins/layui/layui.js"></script>
	
	<?php session('upmsg',null);?>
	<?php session('downmsg',null);?>
</html>