<?php
namespace Home\Controller;
use Think\Controller;
class NoteController extends Controller{

	//处理修改的表单
	public function doedit(){
		//去掉空格
		$id = trim($_POST['id']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$captcha = trim($_POST['captcha']);

		if($title =='')
			$this->error('标题不能为空!');

		if($content =='')
			$this->error('内容不能为空!');
		//验证验证码是否正确
		$verify = new \Think\Verify();
		if(!$verify -> check($captcha))
			$this->error('验证码不正确!');

		/************************上传图片（逻辑问题 往下挪）*************************/
		$upload = new \Think\Upload();//生成类的对象
		//配置以下几个值
		$upload->maxSize = 1024*1024*2;
		$upload->exts =	array('jpg','gif','png','jpeg');
		$upload->rootPath =	'./Public/Uploads/';
		$upload->savePath =	'Note/';//自动创建二级目录，附件上传的子目录

		//开始上传
		$info = $upload->upload();

		//$image='';//**设置图片默认是空，用来解决必须要传图片的问题

		//构造要修改的数据
			$data = [
				'title' => $title,
				'content' => $content,
				'ip' => get_client_ip(1), //如何获取客户端的IP地址,传1是数字，传0是字符串
			];

		//是否上传图片成功
		if($info){
			/*var_dump($info);
			exit;*/
			//下面获取图片路径
			$image['photo'] = $info['image']['savepath'].$info['image']['savename'];
			/*******************生成缩略图**************************/
			//先把缩略图名字起好
			$big_img['photo'] = $info['image']['savepath'].'big_'.$info['image']['savename'];
			$mid_img['photo'] = $info['image']['savepath'].'mid_'.$info['image']['savename'];
			$sm_img['photo'] = $info['image']['savepath'].'sm_'.$info['image']['savename'];

			//开始生成
			$imgObj = new \Think\Image();
			//打开要处理的图片
			$imgObj -> open('./Public/Uploads/'. $image['photo']); 
			$imgObj -> thumb(400,400)->save('./Public/Uploads/'. $big_img['photo']); 
			$imgObj -> thumb(300,300)->save('./Public/Uploads/'.$mid_img['photo']);
			$imgObj -> thumb(100,100)->save('./Public/Uploads/'.$sm_img['photo']);
			
			//把图片路径放到数组一起修改
			$data['image'] = $image['photo'];
			$data['big_img'] = $big_img['photo'];//**命名不支持大写，不理解
			$data['mid_img'] = $mid_img['photo'];
			$data['sm_img'] = $sm_img['photo'];
			//删除原来的图片
			unlink('./Public/Uploads/'.$_POST['oimage']);
			unlink('./Public/Uploads/'.$_POST['obimage']);
			unlink('./Public/Uploads/'.$_POST['omimage']);
			unlink('./Public/Uploads/'.$_POST['osimage']);

		}
		

		//插入数据库并返回新添加记录的ID
		$model = D('Note'); //生成Note类的对象
		$id2 = $model->where([
			'id'=>$id, 
		])-> save($data);

		//提示信息
		$this->success("修改成功!",'/www.shop11.com/Home/Note/lst');
	}

	//修改
	public function edit(){
		//接收ID
		$id = $_GET['id'];
		//根据ID取出留言的标题和内容
		$model = D('Note');
		$info = $model -> where([
			'id'=> $id,
		])->find();

		
		//var_dump($info);
		$this->assign('info',$info);

		//显示修改的表单
		$this->display();
	}

	//批量删除
	public function batchDelete(){
		//把提交的数据都打印出来
		//var_dump($_POST); 
		$model = D('Note');
		//循环每个ID
		foreach ($_POST['id'] as $id) {
		//先取出这个留言的图片路径是什么
		//SELECT * FROM s7_note WHERE id=$id LIMIT 1
			$info = $model->field('image,big_img,mid_img,sm_img') 
				->where([ 
					'id'=>$id,
				])->find();
		//从硬盘上删除这些图片
			unlink('./Public/Uploads/'.$info['image']);
			unlink('./Public/Uploads/'.$info['big_img']);
			unlink('./Public/Uploads/'.$info['mid_img']);
			unlink('./Public/Uploads/'.$info['sm_img']);

		//再从数据库中把这些记录也删除
		//delete from s7_note where id=$i
			$model->where([
				  'id'=>$id,
				])->delete();
		}
		//提示信息并返回上一个页面
		$this->success('操作成功！');
	}

	//删除留言
	public function delete(){
		//接受要删除的ID
		$id = $_GET['id'];
		//从数据库中删除ID=这个ID的记录
		$model = D('Note');
		//先取出这个留言的图片路径是什么
		//SELECT * FROM s7_note WHERE id=$id LIMIT 1
		$info = $model->field('image,big_img,mid_img,sm_img') 
				->where([ 
				  	'id'=>$id,
				])->find();
		//从硬盘上删除这些图片
		unlink('./Public/Uploads/'.$info['image']);
		unlink('./Public/Uploads/'.$info['big_img']);
		unlink('./Public/Uploads/'.$info['mid_img']);
		unlink('./Public/Uploads/'.$info['sm_img']);

		//再从数据库中把这些记录也删除
		//delete from s7_note where id=$i
		$model->where([
			  'id'=>$id,
			])->delete();
		
		//提示信息并返回上一个页面
		$this->success('操作成功！');
	}

	public function captcha(){

		$Verify = new \Think\Verify();
		$Verify -> entry();
	}

	//向表中插入500条记录
	public function testInsert(){
		$str=" 海外网9月7日电国际奥委会副主席约翰·科茨7日表示，“不管有没有新冠病毒”，东京奥运会都将在明年（2021年）如期举行。据英国广播公司（BBC）7日报道，科茨表示东京奥运会将在2021年7月23日如期开幕，他还表示，“复兴奥运是他们提出的口号，2011年他们经历了东日本大地震和海啸。而现在，战胜新冠病毒将成为大会主题，就像隧道尽头的光亮”。
		今年7月份，东京奥组委首席执行官武藤敏郎表示，2021年的奥运会很有可能限制观众人数，但将尽量避免无观众模式。此外，奥运会将精简开闭幕式，并削减各国代表和工作人员的数量。原本有超过1.1万运动员计划参加东京奥运会，但由于日本目前仍对大多数国家关闭国境，具体有多少运动员能最终成行尚未可知。武藤还补充道，“如果有足够的疫苗当然很好，但我们没有说过没有疫苗就不会办奥运会，这不是一个先决条件”。东京奥组委主席森喜朗今年4月曾表示，如果东京无法在2021年举办奥运会，本届奥运会就将被取消。国际奥委会主席巴赫解释称,
		“奥组委不可能长期雇佣3000到5000名工作人员，你不可能每年都更改一次全球体育赛事的整体计划”。（海外网 王西洛）";

		

		$model = D('Note');
		for($i=1;$i<=500;$i++){

			$model->add(
				[
				'title' => mb_substr($str,rand(0,100),rand(5,10),'utf-8'),
				'content' => mb_substr($str,rand(0,200),rand(100,200),'utf-8'),
				'ip' => ip2long(rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'
						.rand(0,255)),
				]
			);
		}
	}
	//查案所有留言
	public function lst(){

		
		/*******************************搜索***********************************/
		$title = isset($_GET['title'])?$_GET['title']:'';
		$start = isset($_GET['start'])?$_GET['start']:'';
		$end = isset($_GET['end'])?$_GET['end']:'';

		$where = [];

		//如果传的值不为空
		if($title){
			//模糊查询
			$where['title'] = ['like',"%$title%"]; 
		}

		if($start && $end){
			$where['addtime'] = ['BETWEEN',[$start,$end]];
		}else if($start){
			$where['addtime'] = ['EGT',$start];
		}else if($end){
			$where['addtime'] = ['ELT',$end];
		}

		
		/*******************************翻页**********************************/

		//去除总记录数
		$model = D('Note');
		$count = $model->count();

		$perPage = 10;	
		$page = new \Think\Page($count,$perPage);//总记录数，每页显示几条

		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');

		//生成翻页用的按钮
		$btn = $page->show();
		$this->assign('page',$btn);

		/*******************************取数据***************************************/

		//取出某一页的数据
		$data = $model->where($where)->order('id DESC')->limit($page->firstRow,$perPage)->select();

		//从表中取出留言
		//$model = D('Note');
		//$data = $model -> select();

		//var_dump($data);

		//传到页面中
		$this -> assign('data',$data);

		/***************************显示页面***************************************/

		//显示一个页面
		$this->display();
	}

	//添加留言-显示表单
	public function add(){

		$this ->display();
	}

	//添加留言-显示表单
	public function doadd(){
		
		//去掉空格
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$captcha = trim($_POST['captcha']);

		if($title =='')
			$this->error('标题不能为空!');

		if($content =='')
			$this->error('内容不能为空!');
		//验证验证码是否正确
		$verify = new \Think\Verify();
		if(!$verify -> check($captcha))
			$this->error('验证码不正确!');

		/*********************上传图片（逻辑问题 往下挪）*************************/
		$upload = new \Think\Upload();//生成类的对象
		//配置以下几个值
		$upload->maxSize = 1024*1024*2;
		$upload->exts =	array('jpg','gif','png','jpeg');
		$upload->rootPath =	'./Public/Uploads/';
		$upload->savePath =	'Note/';//自动创建二级目录，附件上传的子目录

		//开始上传
		$info = $upload->upload();

		//$image='';//**设置图片默认是空，用来解决必须要传图片的问题

		//把将要插入表中的数据放到一个数组中
			$data = [
				'title' => $title,
				'content' => $content,
				'ip' => get_client_ip(1), //如何获取客户端的IP地址,传1是数字，传0是字符串
			];

		if($info){
			/*var_dump($info);
			exit;*/
			//下面获取图片路径
			$image['photo'] = $info['image']['savepath'] .$info['image']
								['savename'];
			/*******************生成缩略图**************************/
			//先把缩略图名字起好
			$big_img['photo'] = $info['image']['savepath'].'big_'.$info['image']
								['savename'];
			$mid_img['photo'] = $info['image']['savepath'].'mid_'.$info['image']
								['savename'];
			$sm_img['photo'] = $info['image']['savepath'].'sm_'.$info['image']
								['savename'];

			//开始生成
			$imgObj = new \Think\Image();
			//打开要处理的图片
			$imgObj -> open('./Public/Uploads/'. $image['photo']); 
			$imgObj -> thumb(400,400)->save('./Public/Uploads/'. $big_img['photo']); 
			$imgObj -> thumb(300,300)->save('./Public/Uploads/'.$mid_img['photo']);
			$imgObj -> thumb(100,100)->save('./Public/Uploads/'.$sm_img['photo']);
			
			//把四个路径放到数组中
			$data['image'] = $image['photo'];
			$data['big_img'] = $big_img['photo'];//**命名不支持大写，不理解
			$data['mid_img'] = $mid_img['photo'];
			$data['sm_img'] = $sm_img['photo'];
			
		}
		/*else{
			//获取失败原因
			$error = $upload->getError();
			//显示哭脸，并返回上一个页面
			$this->error($error);
		} */

		//插入数据库并返回新添加记录的ID
		$model = D('Note'); //生成Note类的对象
		$id = $model->add(

			$data
			/*[
				'title' => $title,
				'content' => $content,
				'ip' => get_client_ip(1), //如何获取客户端的IP地址,传1是数字，传0是字符串
				'image' => $image['photo'],
				'big_Img' => $bigImg['photo'],
				'mid_Img' => $midImg['photo'],
				'sm_Img' => $smImg['photo'],
			]*/
		);
		//提示信息
	$this->success("添加成功：新添加的ID为:$id",'/www.shop11.com/Home/Note/lst');

	}
}