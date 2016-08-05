<?php
//Simple Message Manage
//简单信息管理

//2016.8.5
//By DTSDAO
//Action - User

//接收传入数据
$username = $_POST['username'];
$password = $_POST['password'];
$new_pwd = $_POST['new_password'];

switch($_POST['action']){
	case '登录':
	case '注册':
	case '修改密码':
	case '添加用户':
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
		exit;
}
?>