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
	case '修改密码':
	case '添加用户':
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
		exit;
}
?>