<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>修改留言</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" type="text/css" href="/www.shop11.com/Public/bootstrap-3.3.7-dist/css/bootstrap.css">
</head>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">修改留言</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav"> 
        <li><a href="/www.shop11.com/Home/Note/lst">留言列表</a></li>
      </ul>    
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<body>
	<h1><a class="btn btn-primary"href="/www.shop11.com/Home/Note/lst">留言列表</a></h1>

	<form enctype="multipart/form-data" method="POST" action="/www.shop11.com/index.php/Home/Note/doedit">
		<input type="hidden" name="id" value="<?=$info['id']?>">
		<input type="hidden" name="oimage" value="<?=$info['image']?>">
		<input type="hidden" name="obimage" value="<?=$info['big_img']?>">
		<input type="hidden" name="omimage" value="<?=$info['mid_img']?>">
		<input type="hidden" name="osimage" value="<?=$info['sm_img']?>">
		<div>
			请选择新图片：
			<input size="60" type="file" name="image">
			<br>
			<img src="/www.shop11.com/Public/Uploads/<?=$info['mid_img']?>">

		</div>
		<br>
		<div>
			标题：
			<input value="<?=$info['title']?>" size="35" type="text" name="title">
		</div>
		<div>
			内容：
			<textarea name="content" rows="10" cols ="32" ><?=$info['content']?> </textarea>
		</div>
		<div>
			验证码：
			<img style="cursor:pointer;"
			onclick="this.src='/www.shop11.com/Home/Note/captcha#'+Math.random();" 
			src="/www.shop11.com/Home/Note/captcha"><br>
			<input type="text" name="captcha">
		</div>
		<div>
			<input type="submit" name="添加">
			<input type="reset" name="重新添加">	
		</div>
	</form>
</body>
</html>