<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO
//Login

include "file/essentials.php";

switch($_GET['area']){
	case "用户":
		$page = new THEME("manage_user",$db);
		break;
	case "群组":
		$page = new THEME("manage_group",$db);
		break;
	case "设置":
		$page = new THEME("manage_config",$db);
		break;
	case "信息":
		$page = new THEME("manage_msg",$db);
		break;
	default:
		if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"msg") === false){
			$page = new THEME("manage_user",$db);
		}else
		{
			$page = new THEME("manage_msg",$db);
		}
		break;
}

$db->close();
?>