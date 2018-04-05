<?php
//Simple Message Manage
//简单信息管理

//2016.8.5
//By DTSDAO
//Action - User

//接收传入数据
$username = $_POST['username'];
$password = $_POST['password'];
$newPwd = $_POST['new_password'];
$newPwdConfirm = $_POST['new_pwd_conf'];
$group = $_POST['group'];

switch($_REQUEST['action']){
	case '登录':
		if ($_SESSION[$func->getPre('times')] == 4){
			$theme->divAgc("机会用尽！");
			break;
		}
		
		if ($db->checkPwd($username,$password)){
			$_SESSION[$func->getPre('username')] = $username;
			$_SESSION[$func->getPre('times')] = 0;
			
			$func->turn('manage.php');
			break;
		} else {
			if (!$_SESSION[$func->getPre('times')]){
				$_SESSION[$func->getPre('times')] = 0;
			}
			
			$_SESSION[$func->getPre('times')]++;
			$times = 5-$_SESSION[$func->getPre('times')];
			
			$theme->divAgc("密码错误！你还剩 " . $times . " 次机会!");
		}
		break;
	case '退出':
		if ($_SESSION[$func->getPre('username')] != 'visitor'){
			unset($_SESSION[$func->getPre('username')]);
			unset($_SESSION[$func->getPre('times')]);
			session_destroy;
			
			$func->turn('index.php');
		} else {
			$func->turn('index.php');
		}
		break;
	case '注册':
		if ($db->getConfig("allow_reg") == "off"){
			$theme->divAgc("不允许注册！");
			break;
		}
		
		$db->addUser($username,$password,$db->getConfig("default_group"),$theme);
		$_SESSION[$func->getPre('username')] = $username;
		$_SESSION[$func->getPre('times')] = 0;
		
		$func->turn("manage.php");
		break;
	case '添加用户':
		if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false){
			$theme->divAgc("权限不足！");
			break;
		}
		
		$db->addUser($username,$password,$group,$theme);
		
		$theme->divAgc("添加用户成功！");
		break;
	case '修改密码':
		if ((strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false) && ($_SESSION[$func->getPre('username')] !== $username)){
			$theme->divAgc("权限不足！");
			break;
		}
		
		if (!$db->checkPwd($_SESSION[$func->getPre('username')],$password)){
			$theme->divAgc("旧密码错误！");
			break;
		}
		
		if (($_SESSION[$func->getPre('username')] == $username) && ($newPwd != $newPwdConfirm)){
			$theme->divAgc("两次输入的密码不一致！");
			break;
		}
		
		$db->updateUserPassword($username,$newPwd,$theme);
		$theme->divAgc("修改成功！");
		break;
	case '修改分组':
		if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false){
			$theme->divAgc("权限不足！");
			break;
		}
		
		$db->updateUserGroup($username,$group,$theme);
		$theme->divAgc("修改成功！");
		break;
	case '删除用户':
		if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false){
			$theme->divAgc("权限不足！");
			break;
		}
		
		$db->deleteUser($username,$theme);
		$theme->divAgc("删除成功！");
		break;
	case '建表':
		if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"users") === false){
			$theme->divAgc("权限不足！");
			break;
		}
		
		$db->createMsgTable($username);
		
		$theme->divAgc("建表成功！");
		break;
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
}
?>