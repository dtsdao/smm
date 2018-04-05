<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO

//Default - DTSDAO
//Manage - Header

?>
<?php if ($_SESSION[$this->func->getPre('username')] == "visitor"){
	$this->divAgc("不允许访客访问！");
	include "footer.php";
	exit;
} ?>
<?php 
	$permission = array();
	if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"msg") !== false) $permission['信息'] = true;
	$permission['用户'] = true;
	if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"users") !== false) $permission['群组'] = true;
	if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"config") !== false) $permission['设置'] = true;
	$perm_nums = count($permission); 
?>
			<!-- HEAD -->
			<table width="80%" border="1" align="center">
			  <tr>
				<td colspan="<?php echo $perm_nums; ?>"><h1 align=center><?php echo $this->objectName; ?></h1></td>
				<td width="20%" rowspan="2">
				欢迎,<?php echo $_SESSION[$this->func->getPre('username')]; ?>,<br />
				<a href="action.php?kind=user&action=退出">退出</a>
				<a href="show.php">展示页</a>
				</td>
			  </tr>
			  <tr>
			  
<?php foreach (array_keys($permission) as $perm){?>
				<td width="<?php echo 80 / $perm_nums; ?>%"><a href="manage.php?area=<?php echo $perm; ?>"><?php echo $perm; ?></a></td>
<?php } ?>
				
			  </tr>
			</table>
		<br />
		