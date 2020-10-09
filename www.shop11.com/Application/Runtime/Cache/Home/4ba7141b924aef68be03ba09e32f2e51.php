<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>留言列表</title>
	 <meta charset="utf-8">
	 <link rel="stylesheet" type="text/css" href="/www.shop11.com/Public/bootstrap-3.3.7-dist/css/bootstrap.css">
	 <style type="text/css">
	 	
	 </style>		
</head>
<body>


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">小江的留言板</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">留言列表 <span class="sr-only">(current)</span></a></li>
        <li><a href="/www.shop11.com/Home/Note/add">添加留言</a></li>  
      </ul>
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<h2><a class="btn btn-primary"href="/www.shop11.com/Home/Note/add">添加留言</a></h2>

<form>
		标题：
		<input value="<?=$_GET['title']?>" type="text" name="title">
		时间：从
		<input value="<?=$_GET['start']?>" type="text" name="start">
		到：
		<input value="<?=$_GET['end']?>" type="text" name="end">
		<input type="submit" name="搜索">
</form>

<div class="page"><?=$page?></div>

<form method="POST" action="/www.shop11.com/Home/Note/batchDelete">
	<table border="1" width="100%">

		<tr>
			<td>选择</td>
			<td>ID</td>
			<td>标题</td>
			<td>内容</td>
			<td>IP</td>
			<td>添加时间</td>
			<td>图片</td>
			<td>操作</td>
		</tr>

		<?php foreach($data as $v): ?>
		<tr>
			<td><input type="checkbox" name="id[]" value="<?=$v['id']?>"></td>
			<td><?=$v['id']?></td>
			<td><?=htmlspecialchars($v['title'])?></td> 
			<td><?=mb_substr(htmlspecialchars($v['content']),0,50,'utf8')?>......</td>
			<td><?=long2ip($v['ip'])?></td>
			<td><?=$v['addtime']?></td>
			<td><img src="/www.shop11.com/Public/Uploads/<?=$v['image']?>"></td>
			<td>
				<a href="/www.shop11.com/Home/Note/edit/id/<?=$v['id']?>">修改</a>
				<a onclick="return confirm('确定要删除吗？');" 
				href="/www.shop11.com/Home/Note/delete/id/<?=$v['id']?>">删除</a>
			</td> 
		</tr>
		<?php endforeach?>

	</table>
	<input type="submit" name="" value="删除所选">
</form>
		
<div class="page"><?=$page?></div>

</body>
</html>