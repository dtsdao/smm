<?php
//Simple Message Manage
//简单信息管理

//2016.8.5
//By DTSDAO
//Action - User

//接收传入数据
$user = $_POST['username'];
$perm = $_POST['permission'];
$group = $_POST['group'];

switch($_POST['action']){
	case '增加分组':
	case '增加组中用户':
	case '修改用户分组':
	case '修改分组权限':
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
		exit;
}
?>