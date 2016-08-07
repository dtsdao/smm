<?php
//Simple Message Manage
//简单信息管理

//2016.8.7
//By DTSDAO

//Default - DTSDAO
//Register

?>
<?php include "header.php"; ?>

			<h1 align=center>注册</h1>
			<div align=center>
			<form action="action.php" method="POST">
				<input type="hidden" name="kind" value="user">
				<input type="hidden" name="action" value="注册">
				用户名:<input name="username"><br />
				密码:<input type="password" name="password"><br />
				<input type="submit" value="注册">
			</form>
			</div>

<?php include "footer.php" ?>