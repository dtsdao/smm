<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Package - Essentials

ini_set('error_reporting','E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE');

if (!file_exists("file/config.php")){
	if (!file_exists("install.php")){
		echo "<title>CONFIG_ERROR</title>\n";
		echo "<div align=center>缺少配置文件！</div>";
		exit;
	}
	header("Location: install.php");
}

//import
include "config.php";
include "module/database.php";
include "module/functions.php";
include "theme/load.php";

//classes
$func = new functions($dbConf);
$db = new DB($dbConf,$func);

//de-xss
foreach (array_keys($_POST) as $sign){
	if (!get_magic_quotes_gpc()) $_POST[$sign] = addslashes(htmlspecialchars($_POST[$sign]));
		else $_POST[$sign] = htmlspecialchars($_POST[$sign]);
}

//session
session_start();
if (!$_SESSION[$func->getPre('username')]) $_SESSION[$func->getPre('username')] = "visitor";
?>