<?php
//Simple Message Manage
//简单信息管理

//2016.8.1
//By DTSDAO
//Package - Essentials

ini_set('error_reporting','E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE');

//import
include "config.php";
include "module/database.php";
include "module/functions.php";
include "theme/load.php";

//classes
$func = new functions($dbConf);
$db = new DB($dbConf,$func);

//session
session_start();
if (!$_SESSION[$func->getPre('username')]) $_SESSION[$func->getPre('username')] = "visitor";
?>