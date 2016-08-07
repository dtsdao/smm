<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO

//Default - DTSDAO
//Show

?>
<?php include "header.php"; ?>
<?php 
if (($db->getConfig("showpage") == "off") && (strpos($db->getUserPerm($_SESSION[$this->func->getPre('username')]),"msg") === false)){
	$this->divAgc("权限不足！");
	include "footer.php";
	exit;
}
?>
<?php 
$result = $this->db->getMsgRow();
$id = 0;

if ($result->num_rows > 0) $maxline = $result->num_rows + 1; else $maxline = 1;
//格式化信息
for ($i=1;$i<$maxline;$i++){
	$row = $result->fetch_assoc();
	$rows[$i] = $row;
}
?>
			<!-- HEAD -->
			<table width="80%" border="1" align="center">
			  <tr>
				<td colspan="4"><h1 align=center><?php echo $this->objectName; ?></h1></td>
				<?php if ($_SESSION[$this->func->getPre('username')] != "visitor"){ ?>
					<td width="20%" rowspan="2">
					欢迎,<?php echo $_SESSION[$this->func->getPre('username')]; ?>,<br />
					<a href="action.php?kind=user&action=退出">退出</a>
					<a href="manage.php">管理</a>
					</td>
				<?php } ?>
			  </tr>
			</table>
			
			<br />
			
<?php if ($maxline == 1) { ?>
			<table border=1 align="center" width="80%">
				<tr>
					<td width="100%">空</td>
				</tr>
			</table>
<?php } else { ?>
			<table border=1 align="center" width="80%">
				<!-- 表头 -->
				<tr>
					<th>序号</th>
					<?php foreach ($this->db->getFormat() as $top){ ?>
					<th><?php echo $top; ?></th>
					<?php } ?>
					<th>作者</th>
					<th>时间</th>
				</tr>
				
				<!-- 信息 -->
				<?php for ($i=1;$i<$maxline;$i++){ ?><?php $id = $rows[$i]['id'];?>
					<tr>
						<td><?php echo $id; ?></td>
					<?php foreach ($this->db->getFormat() as $sign){ ?>
							<td><?php echo $rows[$i][$sign]; ?></td>
					<?php } ?>
						<td><?php echo $rows[$i]['author']; ?></td>
						<td><?php echo $rows[$i]['time']; ?></td>
					</tr>
				<?php } ?>
			</table>
			<br />
<?php } ?>
<?php include "footer.php" ?>