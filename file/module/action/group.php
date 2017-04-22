<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO
//Action - Group

//判断权限
if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false){
	$theme->divAgc("权限不足！");
	$theme = new THEME("footer",$db);
	exit;
}

//接收传入数据
$perm = $_POST['permission'];
$group = $_POST['group'];
$members = $_POST['members'];

switch($_REQUEST['action']){
	case '添加分组':
		$db->createGroup($group,$members,$perm,$theme);
		$theme->divAgc("添加分组成功！");
		break;
	case '删除分组':
		$db->deleteGroup($group,$theme);
		$theme->divAgc("删除分组成功！");
		break;
	case '修改分组权限':
		$db->updateGroupPerm($group,$perm,$theme);
		$theme->divAgc("修改组权限成功！");
		break;
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
}
?>