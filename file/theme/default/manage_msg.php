<?php
//Simple Message Manage
//简单信息管理

//2016.8.6
//By DTSDAO

//Default - DTSDAO
//Manage - Msg

?>
<?php include "header.php"; ?>
<?php include "manage_header.php"; ?>

<?php 
if (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"msg") === false){
	$this->divAgc("权限不足！");
	include "footer.php";
	exit;
}
$result = $this->db->getMsgRow();
$maxid = 0;
?>

<table border=1 align="center" width="80%">
	<!-- 表头 -->
	<tr>
		<th>序号</th>
		<?php foreach ($this->db->getFormat() as $top){ ?>
		<th><?php echo $top; ?></th>
		<?php } ?>
		<th>作者</th>
		<th>时间</th>
		<th>操作</th>
	</tr>
	
	<!-- 数据库中信息 -->
	<?php while($row = $result->fetch_array()){ ?>
		<?php if ($row['id'] > $maxid) $maxid = $row['id']; ?>
		<tr><form method="POST" action="action.php">
			<input type="hidden" name="kind" value="msg">
			<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
			<td><?php echo $row['id']; ?></td>
		<?php foreach ($this->db->getFormat() as $sign){ ?>
				<td><input name="<?php echo $sign; ?>" value="<?php echo $row[$sign]; ?>" width="100%"></td>
		<?php } ?>
			<td><?php echo $row['author']; ?></td>
			<td><?php echo $row['time']; ?></td>
			<td>
				<input name="action"   type="submit" value="修改">
				<input name="action"   type="submit" value="删除">
			</td>
		</form></tr>
	<?php } ?>
	
	<!-- 新建条目 -->
	<tr>
		<form method="POST" action="action.php">
			<input type="hidden" name="kind" value="msg">
			<td><?php echo $maxid + 1; ?></td>
			<?php foreach ($this->db->getFormat() as $sign){ ?>
				<td><input name="<?php echo $sign; ?>" width="100%"></td>
			<?php } ?>
			<td><?php echo $_SESSION[$this->func->getPre('username')]; ?></td>
			<td><?php echo date("Y-m-d"); ?></td>
			<td><input name="action"   type="submit" value="新建"></a></td>
		</form>
	</tr>
</table>

<br />

<?php include "footer.php" ?>