<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO

//Default - DTSDAO
//Manage - User

?>
<?php include "header.php"; ?>
<?php include "manage_header.php"; ?>

<form action="action.php" method="POST">
<input type="hidden" name="kind" value="users">
<input type="hidden" name="username" value="<?php echo $_SESSION[$this->func->getPre('username')]; ?>">
	<table width="80%" border="1" align="center">
		<!-- 表头 -->
		<tr>
			<td width="10%" rowspan="4"><h1 align=center>个人设置</h1></td>
			<th width="80%">密码</th>
			<th width="10%" rowspan="4"><input name="action"   type="submit" value="修改密码"></th>
		</tr>
		
		<!-- 表单 -->
		<tr>
			<td>旧密码：<input name="password"></td>
		</tr>
		
		<tr>
			<td>新密码：<input name="new_password"></td>
		</tr>
		
		<tr>
			<td>新密码确认：<input name="new_pwd_conf"></td>
		</tr>
	</table>
</form>

<br />

<?php 
if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"users") !== false){
	$result = $this->db->getUserRow();

	if ($result->num_rows > 0) $maxline = $result->num_rows + 1; else $maxline = 1;
	//格式化信息
	for ($i=1;$i<$maxline;$i++){
		$row = $result->fetch_assoc();
		$rows[$i] = $row;
	}
?>
	<table width="80%" border="1" align="center">
		<!-- 表头 -->
		<tr>
			<td width="10%" rowspan="<?php echo $result->num_rows + 2; ?>"><h1 align=center>用户</h1></td>
			<th width="5%">序号</th>
			<th width="20%">用户名</th>
			<th width="20%">密码</th>
			<th width="15%">群组</th>
			<th width="30%">操作</th>
		</tr>
		
		<!-- 取得数据库中信息 -->
		<?php for ($i=1;$i<$maxline;$i++){ ?>
			<tr>
				<form action="action.php" method="POST">
				<input type="hidden" name="kind" value="user">
				<input type="hidden" name="username" value="<?php echo $rows[$i]['username']; ?>">
					<td><?php $id = $rows[$i]['id']; echo $id; ?></td>
					<td><?php echo $rows[$i]['username']; ?></td>
					<td><input name="password"></td>
					<td><input name="group" value="<?php echo $rows[$i]['groupname']; ?>"></td>
					<td>
						<input name="action" type="submit" value="修改密码">
						<input name="action" type="submit" value="修改分组">
						<input name="action" type="submit" value="删除用户">
						<input name="action" type="submit" value="建表">
					</td>
				</form>
			</tr>
		<?php } ?>
		
		<!-- 添加用户 -->
		<tr>
			<form action="action.php" method="POST">
			<input type="hidden" name="kind" value="user">
				<td><?php echo $id + 1; ?></td>
				<td><input name="username"></td>
				<td><input name="password"></td>
				<td><input name="group"></td>
				<td>
					<input name="action" type="submit" value="添加用户">
				</td>
			</form>
		</tr>
	</table>

	<br />
<?php } ?>

<?php include "footer.php" ?>