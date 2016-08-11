<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO

//Default - DTSDAO
//Manage - Config

?>
<?php include "header.php"; ?>
<?php include "manage_header.php"; ?>

<?php 
if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"config") === false){
	$this->divAgc("权限不足！");
	include "footer.php";
	exit;
}
?>

<?php 
$result = $this->db->getConfigRow();

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
		<td width="10%" rowspan="<?php echo $result->num_rows + 1; ?>"><h1 align=center>设置</h1></td>
		<th width="40%">名称</th>
		<th width="40%">值</th>
		<th width="10%">操作</th>
	</tr>
	
	<!-- 取得数据库中信息 -->
	<?php for ($i=1;$i<$maxline;$i++){ ?>
		<tr>
			<form action="action.php" method="POST">
			<input type="hidden" name="kind" value="config">
			<input type="hidden" name="name" value="<?php echo $rows[$i]['name']; ?>">
				<td><?php echo $rows[$i]['name']; ?></td>
				<td><input name="value" value="<?php echo $rows[$i]['value']; ?>"></td>
				<td>
					<input name="action"   type="submit" value="修改设置">
				</td>
			</form>
		</tr>
	<?php } ?>
</table>

<br/>

<div style="color: red" align=center>温馨提示：请确保数据库信息准确且可用！</div>

<table width="80%" border="1" align="center">
	<!-- 表头 -->
	<tr>
		<td width="10%" rowspan="8"><h1 align=center>数据库设置</h1></td>
		<th width="40%">名称</th>
		<th width="40%">值</th>
		<th width="10%" rowspan="8"><input name="action"   type="submit" value="修改数据库设置"></th>
	</tr>
	
	<!-- 信息 -->
	<form action="action.php" method="POST">
	<input type="hidden" name="kind" value="config">
		<tr>
			<td>地址</td>
			<td><input name="host" value="<?php echo $this->db->dbConf['host']; ?>"></td>
		</tr>
		<tr>
			<td>用户名</td>
			<td><input name="user" value="<?php echo $this->db->dbConf['user']; ?>"></td>
		</tr>
		<tr>
			<td>密码</td>
			<td><input name="pwd" value="<?php echo $this->db->dbConf['pwd']; ?>"></td>
		</tr>
		<tr>
			<td>数据库名称</td>
			<td><input name="db" value="<?php echo $this->db->dbConf['db']; ?>"></td>
		</tr>
		<tr>
			<td>端口</td>
			<td><input name="port" value="<?php echo $this->db->dbConf['port']; ?>"></td>
		</tr>
		<tr>
			<td>前缀</td>
			<td><input name="prefix" value="<?php echo $this->db->dbConf['prefix']; ?>"></td>
		</tr>
	</form>
</table>

<br />

<?php include "footer.php" ?>