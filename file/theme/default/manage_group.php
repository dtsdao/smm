<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO

//Default - DTSDAO
//Manage - Group

?>
<?php include "header.php"; ?>
<?php 
	if ($_SESSION[$this->func->getPre('username')] == "visitor"){
		$this->divAgc("未登录！");
		include "footer.php";
		exit;
	}
?>
<?php include "manage_header.php"; ?>

<?php include "footer.php" ?>