<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Index

if (!file_exists("file/config.php")){
	if (!file_exists("install.php")){
		echo "<title>CONFIG_ERROR</title>\n";
		echo "<div align=center>缺少配置文件！</div>";
		exit;
	}
	header("Location: install.php");
}

include "file/essentials.php";

$page = new THEME("index",$db);

$db->close();
?>