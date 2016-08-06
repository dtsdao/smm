<?php
//Simple Message Manage
//简单信息管理

//2016.8.5
//By DTSDAO
//Action

include "file/essentials.php";

//输出html文件开始
$theme = new THEME('header',$db,'MSG');

//接收传入数据
$kind = $_REQUEST['kind'];
$fileAddress = 'file/module/action/' . $kind . '.php';

switch($kind){
	case 'user':
	case 'group':
	case 'msg':
	case 'config':
		if (!file_exists($fileAddress)){
			$theme->divAgc("缺少 " . $kind . " 这个action文件");
			break;
		}
		include $fileAddress;
		break;
	default:
		$theme->divAgc("没有传入引用类别！请检查主题文件！");
		break;
		exit;
}

//输出html文件结束
$theme = new THEME('footer',$db);

$db->close();
?>