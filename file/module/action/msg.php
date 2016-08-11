<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO
//Action - Msg

//判断权限
if (strpos($db->getUserPerm($_SESSION[$func->getPre('username')]),"msg") === false){
	$theme->divAgc("权限不足！");
	$theme = new THEME("footer",$db);
	exit;
}

//接收传入数据
$id = $_POST['id'];
$msg = array();
foreach ($db->getFormat() as $sign){
	if (!get_magic_quotes_gpc()) $msg[$sign] = addslashes(htmlspecialchars($_POST[$sign]));
		else $msg[$sign] = htmlspecialchars($_POST[$sign]);
}

switch($_REQUEST['action']){
		case "修改":
		foreach ($db->getFormat() as $sign){
			$db->updateMsg($sign,$msg[$sign],$id,$theme);
		}
		$db->updateMsg("author",$_SESSION[$func->getPre('username')],$id,$theme);
		$db->updateMsg("time",date("Y-m-d"),$id,$theme);
		
		$theme->divAgc("修改成功！");
		break;
	case "新建":
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
		$db->deleteMsg($id,$theme);
		
		$theme->divAgc("删除成功！");
		break;
	default:
		$theme->divAgc("没有传入操作！请检查主题文件！");
		break;
}
?>