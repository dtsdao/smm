<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO
//Action - Config

//判断权限
if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"config") === false){
	$theme->divAgc("权限不足！");
	$theme = new THEME("footer",$db);
	exit;
}

//接收传入数据
$name = $_POST['name'];
$value = $_POST['value'];
$newDbConf = array(
	'host' => $_POST['host'],
	'user' => $_POST['user'],
	'pwd' => $_POST['pwd'],
	'db' => $_POST['db'],
	'port' => $_POST['port'],
	'prefix' => $_POST['prefix']
);

switch($_REQUEST['action']){
	case '修改设置':
		$db->updateConfig($name,$value,$theme);
		$theme->divAgc("修改设置成功！");
		break;
	case '修改数据库设置':
		$theme->divAgc("提示：若未提示成功则为文件系统出错！");
		$func->updateDbConf($newDbConf,$theme);
		$theme->divAgc("修改数据库设置成功！");
		break;
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
}
?>