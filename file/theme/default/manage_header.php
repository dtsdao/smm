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
			<!-- HEAD -->
			<table width="80%" border="1" align="center">
			  <tr>
				<td colspan="4"><h1 align=center><?php echo $this->objectName; ?></h1></td>
				<td width="20%" rowspan="2">
				欢迎,<?php echo $_SESSION[$this->func->getPre('username')]; ?>,<br />
				<a href="action.php?kind=user&action=退出">退出</a>
				<a href="show.php">展示页</a>
				</td>
			  </tr>
			  <tr>
				<td width="20%"><a href="manage.php?area=信息">信息</a></td>
				<td width="20%"><a href="manage.php?area=用户">用户</a></td>
				<td width="20%"><a href="manage.php?area=群组">群组</a></td>
				<td width="20%"><a href="manage.php?area=设置">设置</a></td>
			  </tr>
			</table>
		<br />