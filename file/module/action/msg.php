<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO
//Action - Msg

//接收传入数据
$id = $_POST['id'];
$msg = array();
foreach ($db->getFormat() as $sign){
	if (!get_magic_quotes_gpc()) $msg[$sign] = addslashes($_POST[$sign]);
}

switch($_REQUEST['action']){
		case "修改":
		if ($_SESSION[$func->getPre('username')] == "visitor"){
			$theme->divAgc("未登录！");
			$theme = new THEME('footer',$db);
			exit;
		}
		
		foreach ($db->getFormat() as $sign){
			$db->updateMsg($sign,$msg[$sign],$id);
		}
		$db->updateMsg("author",$_SESSION[$func->getPre('username')],$id);
		$db->updateMsg("time",date("Y-m-d"),$id);
		
		$theme->divAgc("修改成功！");
		break;
	case "新建":
		if ($_SESSION[$func->getPre('username')] == "visitor"){
			$theme->divAgc("未登录！");
			$theme = new THEME('footer',$db);
			exit;
		}
		
		$list = "null";
		
		foreach ($db->getFormat() as $sign){
			$list .= ",'" . $msg[$sign] . "'";
		}
		$list .= ",'" . $_SESSION[$func->getPre('username')] . "'";
		$list .= ",'" . date("Y-m-d") . "'";
		
		$db->createMsg($list,$theme);
		
		$theme->divAgc("添加成功！");
		break;
	case "删除":
		if ($_SESSION[$func->getPre('username')] == "visitor"){
			$theme->divAgc("未登录！");
			$theme = new THEME('footer',$db);
			exit;
		}
		
		$db->deleteMsg($id,$theme);
		
		$theme->divAgc("删除成功！");
		break;
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
		exit;
}
?>