<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO

//Default - DTSDAO
//Manage - Group

?>
<?php include "header.php"; ?>

			<!-- HEAD -->
			<table width="80%" border="1" align="center">
			  <tr>
				<td colspan="4"><h1 align=center><?php echo $this->objectName; ?></h1></td>
				<td width="20%" rowspan="2">
				Welcome <?php echo $_SESSION[$this->func->getPre('username')]; ?>,<br />
				Logout Showpage
				</td>
			  </tr>
			  <tr>
				<td width="20%">信息</td>
				<td width="20%">用户</td>
				<td width="20%">分组</td>
				<td width="20%">系统设置</td>
			  </tr>
			</table>

<?php include "footer.php" ?>