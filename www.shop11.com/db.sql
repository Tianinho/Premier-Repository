create table s11_note
(
	id mediumint unsigned not null auto_increment comment 'id',
	title varchar(100) not null comment '标题',
	content longtext not null comment '内容',
	ip int not null comment 'IP地址', /*char(15)*/
	/*addtime timestamp not null default current_timestamp on update current_timestamp comment '添加时间',*/
	image varchar(100) not null comment'图片路径';
	big_Img varchar(100) not null comment'大图片路径';
	mid_Img varchar(100) not null comment'中图片路径';
	sm_Img varchar(100) not null comment'小图片路径';
	primary key(id)
)engine=InnoDB default charset utf8 comment '留言表';


