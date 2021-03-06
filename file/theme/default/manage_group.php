<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO

//Default - DTSDAO
//Manage - Group

?>
<?php include "header.php"; ?>
<?php include "manage_header.php" ?>

<?php 
if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"users") === false){
	$this->divAgc("权限不足！");
	include "footer.php";
	exit;
}

$result = $this->db->getGroupRow();
?>

<table width="80%" border="1" align="center">
	<!-- 表头 -->
	<tr>
		<td width="10%" rowspan="<?php echo $result->num_rows + 2; ?>"><h1 align=center>群组</h1></td>
		<th width="15%">群组</th>
		<th width="20%">成员</th>
		<th width="35%">权限</th>
		<th width="20%">操作</th>
	</tr>
	
	<!-- 取得数据库中信息 -->
	<?php while($row = $result->fetch_array()){ ?>
		<tr>
			<form action="action.php" method="POST">
			<input type="hidden" name="kind" value="group">
			<input type="hidden" name="group" value="<?php echo $row['name']; ?>">
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['member']; ?></td>
				<td><input name="permission" value="<?php echo $row['permission']; ?>"></td>
				<td>
					<input name="action"   type="submit" value="修改分组权限">
					<input name="action"   type="submit" value="删除分组">
				</td>
			</form>
		</tr>
	<?php } ?>
	
	<!-- 新建群组 -->
	<tr>
		<form action="action.php" method="POST">
		<input type="hidden" name="kind" value="group">
			<td><input name="group"></td>
			<td>自动添加</td>
			<td><input name="permission"></td>
			<td>
				<input name="action"   type="submit" value="添加分组">
			</td>
		</form>
	</tr>
</table>

<br />

<?php include "footer.php" ?>